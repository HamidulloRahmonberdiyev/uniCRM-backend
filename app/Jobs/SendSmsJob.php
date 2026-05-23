<?php

namespace App\Jobs;

use App\Services\Sms\EskizService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    public $backoff = [10, 30, 60];

    public function __construct(
        public string|array $phone,
        public string|array $message,
        public string $from = '4546'
    ) {
        $this->onQueue('sms');
    }

    public function handle(EskizService $eskizService): void
    {
        if (is_string($this->phone)) {
            $eskizService->send($this->phone, $this->message, $this->from);
            return;
        }

        foreach ($this->phone as $index => $phone) {
            try {
                $message = is_array($this->message) ? $this->message[$index] : $this->message;
                $eskizService->send($phone, $message, $this->from);

                if ($index < count($this->phone) - 1) {
                    usleep(100000);
                }
            } catch (\Exception $e) {
                Log::error('SMS xatolik', ['phone' => $phone, 'error' => $e->getMessage()]);
            }
        }
    }
}
