
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('city');
        const dropdown = document.getElementById('autocomplete-dropdown');
        const apiKey = '16cb60ef20844b3db9ba3b786871fd11'; // Your Geoapify API key

        input.addEventListener('input', () => {
            const query = input.value.trim();

            if (query.length < 3) {
                dropdown.innerHTML = '';
                return;
            }
           

            fetch(`https://api.geoapify.com/v1/geocode/autocomplete?text=${query}&apiKey=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    const suggestions = data.features.map(feature => {
                        const city = feature.properties.name;
                        const country = feature.properties.country; // Country information
                        return `${city}, ${country}`; // Format as "City, Country"
                    });
                    dropdown.innerHTML = suggestions.map(name => `<div class="autocomplete-suggestion px-4 py-2 cursor-pointer hover:bg-gray-200 transition-colors duration-150 ease-in-out">
    ${name}
</div>`).join('');
                })
                .catch(error => console.error('Error:', error));
        });

        dropdown.addEventListener('click', (event) => {
            if (event.target.classList.contains('autocomplete-suggestion')) {
                input.value = event.target.textContent;
                dropdown.innerHTML = ''; // Clear dropdown after selection
            }
        });
    });