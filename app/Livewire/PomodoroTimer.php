<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PomodoroTimer extends Component
{
    public $timeRemaining;
    public $isRunning = false;
    public $interval = 30; // Valor por defecto
    public $timerId;
    public $completedPomodoros = 0;

    protected $listeners = ['timerFinished' => 'handleTimerFinished'];

    public function mount()
    {
        $this->resetTimer();
    }

    public function resetTimer()
    {
        $this->timeRemaining = $this->interval * 60;
        $this->isRunning = false;
    }

    public function startTimer()
    {
        if (!$this->isRunning) {
            $this->isRunning = true;
            $this->timerId = $this->dispatchBrowserEvent('start-timer', [
                'duration' => $this->timeRemaining
            ]);
        }
    }

    public function pauseTimer()
    {
        $this->isRunning = false;
        $this->dispatchBrowserEvent('pause-timer');
    }

    public function handleTimerFinished()
    {
        $this->isRunning = false;
        $this->completedPomodoros++;
        $this->playSound();
    }

    public function playSound()
    {
        $this->dispatchBrowserEvent('play-sound');
    }

    public function setInterval($minutes)
    {
        $this->interval = $minutes;
        $this->resetTimer();
    }

    public function formatTime($seconds)
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function render()
    {
        return view('livewire.pomodoro-timer');
    }
}