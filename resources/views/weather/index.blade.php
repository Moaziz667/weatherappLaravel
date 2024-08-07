<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js.js') }}"></script>
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>body {
        font-family: 'Poppins', sans-serif;
    }
        /* Light theme styles */
        .light-theme {
            background-color: #f9f9f9;
            color: #333;
        }
        .light-theme .bg-gray-800 {
            background-color: #f9f9f9;
        }
        .light-theme .text-white {
            color: #333;
        }
        .light-theme .bg-gray-700 {
            background-color: #e0e0e0;
        }
        .light-theme .text-gray-300 {
            color: #333;
        }

        /* Dark theme styles (default) */
        .dark-theme {
            background-color: #2d3748;
            color: #ffffff;
        }
        .dark-theme .bg-gray-800 {
            background-color: #2d3748;
        }
        .dark-theme .text-white {
            color: #ffffff;
        }
        .dark-theme .bg-gray-700 {
            background-color: #4a5568;
        }
        .dark-theme .text-gray-300 {
            color: #e2e8f0;
        }
        
    </style>
</head>
<body class="flex justify-center items-center min-h-screen p-4">
    <div class="relative bg-gray-800 text-white rounded-2xl shadow-lg max-w-full md:max-w-lg lg:max-w-xl w-full p-4 md:p-6 h-auto max-h-[90vh]">
        <!-- Header with time and icons -->
        <div class="flex justify-between items-center mb-4 md:mb-6">
            @php
                $gmtTime = now()->utc()->format('H:i'); // Formats time to 24-hour format
            @endphp

            <div class="text-sm md:text-base font-normal">(GMT): {{ $gmtTime }} 
            </div>
            <div class="flex space-x-2">
                <div class="w-5 h-5 md:w-6 md:h-6 bg-gray-600 rounded-full"></div> <!-- Battery icon -->
                <div class="w-5 h-5 md:w-6 md:h-6 bg-gray-600 rounded-full"></div> <!-- WiFi icon -->
                <div class="w-4 h-4 md:w-5 md:h-5 bg-gray-600 rounded-full"></div> <!-- Signal icon -->
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('weather.index') }}" method="GET" class="relative mb-4">
            <label for="city" class="block text-lg font-medium mb-2 text-white">City:</label>
            <div class="flex items-center space-x-2">
                <input type="text" id="city" name="city" value="{{ request('city') }}" required autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-500 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" id="darkmode" class="bg-gray-800 p-2 rounded-md hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <img src="{{ asset('/images/sun.svg') }}" alt="Sun Icon" id="image" class="w-13 h-10 object-fill" />
                </button>
            </div>
            
            <button type="submit" class="bg-gradient-to-r from-blue-400 to-blue-600 text-white mt-2 px-4 py-2 rounded-lg text-sm font-semibold shadow-lg hover:from-blue-500 hover:to-blue-700 transform transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Get Weather
            </button>
        
            <div id="autocomplete-dropdown" class="absolute w-full mt-2 bg-gray-800 border border-gray-600 hover:text-black rounded-md shadow-lg z-10 text-white max-h-60 overflow-auto">
               
            </div>
        </form>
        
        <!-- Weather Information -->
        @if(isset($weather['main']))
        <div class="flex flex-col items-center bg-gray-800 p-6 rounded-lg shadow-lg">
            @php
                $temperature = $weather['main']['temp'];
                $feelsLike = $weather['main']['feels_like'] - 273.15; // Convert Kelvin to Celsius
                $humidity = $weather['main']['humidity'];
                $pressure = $weather['main']['pressure'];
                $windSpeed = $weather['wind']['speed'];
                $sunrise = \Carbon\Carbon::createFromTimestamp($weather['sys']['sunrise'])->format('H:i');
                $sunset = \Carbon\Carbon::createFromTimestamp($weather['sys']['sunset'])->format('H:i');
            @endphp

            <div class="text-xl md:text-3xl lg:text-4xl font-semibold mb-1">{{ $weather['name'] ?? 'City Name' }}</div>
            <div class="text-xs md:text-sm">{{ $weather['sys']['country'] ?? 'Country' }}</div>
            <div class="text-5xl md:text-6xl lg:text-7xl font-bold mt-2 mb-4 text-center">{{ number_format($temperature, 1) }}°C</div>

            <!-- Weather Icon -->
            @if($temperature <= 0)
                <img 
                  src="{{ asset('/images/snow.png') }}" 
                  alt="Cold Weather Icon" 
                  class="h-32 w-32 md:h-40 md:w-40 lg:h-48 lg:w-48 object-cover rounded-lg"
                >
            @elseif($temperature > 0 && $temperature <= 10)
                <img 
                  src="{{ asset('/images/snow.png') }}" 
                  alt="Cool Weather Icon" 
                  class="h-32 w-32 md:h-40 md:w-40 lg:h-48 lg:w-48 object-cover rounded-lg"
                >
            @elseif($temperature > 10 && $temperature <= 20)
                <img 
                  src="{{ asset('/images/rain.png') }}" 
                  alt="Moderate Weather Icon" 
                  class="h-32 w-32 md:h-40 md:w-40 lg:h-48 lg:w-48 object-cover rounded-lg"
                >
            @elseif($temperature > 20 && $temperature <= 30)
                <img 
                  src="{{ asset('/images/cs.png') }}" 
                  alt="Warm Weather Icon" 
                  class="h-32 w-32 md:h-40 md:w-40 lg:h-48 lg:w-48 object-cover rounded-lg"
                >
            @else
                <img 
                  src="{{ asset('/images/suuny.png') }}" 
                  alt="Hot Weather Icon" 
                  class="h-32 w-32 md:h-40 md:w-40 lg:h-48 lg:w-48 object-cover rounded-lg"
                >
            @endif
    
            <!-- Weather Description -->
            <div class="text-sm md:text-lg lg:text-xl font-semibold text-white text-center mt-2">
              {{ $weather['weather'][0]['description'] ?? 'Description' }}
            </div>

            <!-- Additional Information with Icons -->
            <div id="info" class="bg-gray-700 p-4 rounded-lg shadow-lg mt-6 w-full text-black">
                <div id='in' class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-gray-300">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-temperature-low text-blue-400"></i>
                        <span class="ml-2">Feels Like:</span>
                        <span>{{ number_format($feelsLike, 1) }}°C</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tachometer-alt text-green-400"></i>
                        <span class="ml-2">Humidity:</span>
                        <span>{{ $humidity }}%</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-compress text-red-400"></i>
                        <span class="ml-2">Pressure:</span>
                        <span>{{ $pressure }} hPa</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-wind text-gray-400"></i>
                        <span class="ml-2">Wind Speed:</span>
                        <span>{{ $windSpeed }} m/s</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sun text-yellow-400"></i>
                        <span class="ml-2">Sunrise:</span>
                        <span>{{ $sunrise }} UTC</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sunset text-orange-400"></i>
                        <span class="ml-2">Sunset:</span>
                        <span>{{ $sunset }} UTC</span>
                    </div>
                </div>
            </div>
        </div>
        @else
            <p class="text-center text-red-500 mt-4">Weather information not available.</p>
        @endif
    </div>
    <script>
       document.addEventListener('DOMContentLoaded', () => {
    // Apply saved theme
    const savedTheme = localStorage.getItem('theme') || 'dark-theme';
    document.body.classList.add(savedTheme);
    console.log('Loaded theme:', savedTheme); // Debugging line

    updateThemeIcon(savedTheme);

    document.getElementById('darkmode').addEventListener('click', () => {
        const currentTheme = document.body.classList.contains('dark-theme') ? 'dark-theme' : 'light-theme';
        const newTheme = currentTheme === 'dark-theme' ? 'light-theme' : 'dark-theme';
        
        console.log('Current theme:', currentTheme);
        console.log('New theme:', newTheme);
        
        document.body.classList.remove(currentTheme);
        document.body.classList.add(newTheme);
    
        localStorage.setItem('theme', newTheme);

        updateThemeIcon(newTheme);
    });
});

function updateThemeIcon(theme) {
    const icon  = document.getElementById('image');
    if (theme === 'dark-theme') {
        icon.src = "{{ asset('/images/sun.svg') }}"; 
        icon.alt = 'Sun Icon';
    } else {
        icon.src = "{{ asset('/images/moon.svg') }}"; 
        icon.alt = 'Moon Icon';
    }
}

      
    </script>
</body>
</html>
