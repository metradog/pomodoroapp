<div class="min-h-screen bg-gray-900 text-gray-100 flex flex-col items-center justify-center p-4">
    <h1 class="text-4xl font-bold mb-8">Pomodoro Timer</h1>
    
    <div class="bg-gray-800 rounded-lg shadow-xl p-8 w-full max-w-md">
        <!-- Selector de intervalos -->
        <div class="flex justify-center space-x-4 mb-6">
            <button 
                wire:click="setInterval(5)" 
                class="px-4 py-2 rounded-lg {{ $interval == 5 ? 'bg-indigo-600' : 'bg-gray-700' }} hover:bg-indigo-500 transition"
            >
                5 Min
            </button>
            <button 
                wire:click="setInterval(10)" 
                class="px-4 py-2 rounded-lg {{ $interval == 10 ? 'bg-indigo-600' : 'bg-gray-700' }} hover:bg-indigo-500 transition"
            >
                10 Min
            </button>
            <button 
                wire:click="setInterval(30)" 
                class="px-4 py-2 rounded-lg {{ $interval == 30 ? 'bg-indigo-600' : 'bg-gray-700' }} hover:bg-indigo-500 transition"
            >
                30 Min
            </button>
        </div>
        
        <!-- Temporizador -->
        <div class="text-center mb-8">
            <div class="text-7xl font-mono font-bold my-6">
                {{ $this->formatTime($timeRemaining) }}
            </div>
            
            <div class="flex justify-center space-x-4">
                @if($isRunning)
                    <button 
                        wire:click="pauseTimer" 
                        class="px-6 py-3 bg-red-600 rounded-lg hover:bg-red-500 transition flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Pausa
                    </button>
                @else
                    <button 
                        wire:click="startTimer" 
                        class="px-6 py-3 bg-green-600 rounded-lg hover:bg-green-500 transition flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Iniciar
                    </button>
                @endif
                
                <button 
                    wire:click="resetTimer" 
                    class="px-6 py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition"
                >
                    Reiniciar
                </button>
            </div>
        </div>
        
        <!-- Contador de Pomodoros -->
        <div class="text-center text-lg">
            Pomodoros completados: <span class="font-bold">{{ $completedPomodoros }}</span>
        </div>
    </div>
    
    <!-- Audio para la notificación -->
    <audio id="timerSound" src="https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3" preload="auto"></audio>
    
    <!-- Scripts de Livewire y temporizador -->
    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                let timer;
                
                Livewire.on('start-timer', (data) => {
                    const duration = data.duration;
                    let timeLeft = duration;
                    
                    clearInterval(timer);
                    
                    timer = setInterval(() => {
                        timeLeft--;
                        
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            Livewire.emit('timerFinished');
                        } else {
                            Livewire.emit('timerTick', timeLeft);
                        }
                    }, 1000);
                });
                
                Livewire.on('pause-timer', () => {
                    clearInterval(timer);
                });
                
                Livewire.on('play-sound', () => {
                    document.getElementById('timerSound').play();
                });
                
                Livewire.on('timerTick', (timeLeft) => {
                    @this.set('timeRemaining', timeLeft);
                });
            });
        </script>
    @endpush
</div>