<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="manifest" href="/site.webmanifest">
<link rel="icon" href="/resources/images/Logo%20small.svg">
<script type="text/javascript" src="resources/JS/interaction.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&D&display=swap" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&D&display=swap" media="print" onload="this.media='all'" />
<script>
    if ('serviceWorker' in navigator) {
        // Register a service worker hosted at the root of the
        // site using the default scope.
        navigator.serviceWorker.register('/sw.js').then(
            (registration) => {
                console.log('Service worker registration succeeded:', registration)
            },
            /*catch*/ (error) => {
                console.log('Service worker registration failed:', error)
            }
        )
    } else {
        console.log('Service workers are not supported.')
    }
</script>