<?php

namespace App\Services\Sms;

use App\Exceptions\SmsException;
use App\Jobs\SendSmsJob;
use App\Models\EskizAccount;
use App\Traits\ApiRequestsTrait;

class EskizService
{
  use ApiRequestsTrait;

  private string $baseUrl;

  public function __construct()
  {
    $this->baseUrl = config('services.sms.eskiz.base_url', 'https://notify.eskiz.uz/api');

    if (empty($this->baseUrl)) {
      throw new \RuntimeException('Eskiz base URL topilmadi');
    }
  }

  public function sendOne(string $phone, string $message, string $from = '4546'): void
  {
    SendSmsJob::dispatch($phone, $message, $from);
  }

  public function sendMany(array $recipients, string $from = '4546'): void
  {
    if (empty($recipients)) {
      throw new \InvalidArgumentException('Qabul qiluvchilar ro\'yxati bo\'sh.');
    }

    $phones   = array_column($recipients, 'phone');
    $messages = array_column($recipients, 'message');

    $phonesChunks   = array_chunk($phones, 500);
    $messagesChunks = array_chunk($messages, 500);

    foreach ($phonesChunks as $i => $chunk) {
      SendSmsJob::dispatch($chunk, $messagesChunks[$i], $from)
        ->delay(now()->addMinutes($i * 2));
    }
  }

  public function send(string $phone, string $message, string $from = '4546'): array
  {
    $this->validate($phone, $message, $from);

    $account = $this->getAccount();
    $token = $this->getToken($account);

    return $this->sendRequest($account, $token, $phone, $message, $from);
  }

  private function sendRequest(
    EskizAccount $account,
    string $token,
    string $phone,
    string $message,
    string $from,
    bool $isRetry = false
  ): array {
    $response = $this->request(
      'post',
      "{$this->baseUrl}/message/sms/send",
      $token,
      [
        'mobile_phone' => formatPhoneSms($phone),
        'message' => $message,
        'from' => $from,
      ]
    );

    if ($response->status() === 401 && !$isRetry) {
      $newToken = $this->refreshToken($account);

      return $this->sendRequest($account, $newToken, $phone, $message, $from, true);
    }

    if (!$response->successful()) {
      $error = $response->json('message') ?? $response->body();
      throw new SmsException("SMS xatolik: {$error}");
    }

    return $response->json();
  }

  private function getToken(EskizAccount $account): string
  {
    if ($account->token) return $account->token;

    return $this->refreshToken($account);
  }

  private function refreshToken(EskizAccount $account): string
  {
    $response = $this->request(
      'post',
      "{$this->baseUrl}/auth/login",
      null,
      [
        'email' => $account->email,
        'password' => $account->password,
      ]
    );

    if (!$response->successful()) {
      throw new SmsException('Auth xatolik: ' . $response->body());
    }

    $token = $response->json('data.token');

    if (empty($token)) {
      throw new SmsException('Token topilmadi');
    }

    $account->update([
      'token' => $token,
      'token_updated_at' => now(),
    ]);

    return $token;
  }

  private function getAccount(): EskizAccount
  {
    $account = EskizAccount::where('is_active', true)->first();

    if (!$account) {
      throw new SmsException('Faol akkaunt topilmadi');
    }

    return $account;
  }

  private function validate(string $phone, string $message, string $from): void
  {
    if (empty($phone)) {
      throw new \InvalidArgumentException('Telefon bo\'sh');
    }

    if (empty($message)) {
      throw new \InvalidArgumentException('Xabar bo\'sh');
    }

    if (mb_strlen($message) > 1000) {
      throw new \InvalidArgumentException('Xabar 1000 belgidan oshmasin');
    }
  }
}
