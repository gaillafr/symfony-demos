(function() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('today-date').innerText = new Date().toLocaleDateString('en-US', options);
})();
