<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PomodoroTimer extends Component
{
    public $timeRemaining;
    public $isRunning = false;
    public $interval = 30; // Valor por defecto (30 minutos)
    public $completedPomodoros = 0;

    // En Livewire 3 ya no es necesario $listeners de esta forma
    // Los eventos se manejan directamente con $dispatch

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
            
            // En Livewire 3 usamos $this->dispatch directamente
            $this->dispatch('start-timer', duration: $this->timeRemaining);
        }
    }

    public function pauseTimer()
    {
        $this->isRunning = false;
        $this->dispatch('pause-timer');
    }

    // Manejador de evento desde JavaScript
    public function onTimerFinished()
    {
        $this->isRunning = false;
        $this->completedPomodoros++;
        $this->playSound();
    }

    public function playSound()
    {
        $this->dispatch('play-sound');
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