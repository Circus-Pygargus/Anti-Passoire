document.addEventListener("DOMContentLoaded", () => {
    let flashMessageDuration = 4000;
    const flashMessageContainers = document.querySelectorAll('div.alert[role="alert"]');
    const mainDiv = document.querySelector('.content');

    flashMessageContainers.forEach(flashMessageContainer => {

        const animTimeOut = setTimeout(() => {
            mainDiv.removeChild(flashMessageContainer);
        }, flashMessageDuration + 1000);

        flashMessageContainer.addEventListener('click', (e) => {
            const notif = e.target.tagName === 'DIV' ? e.target : e.target.parentNode;

            // User needs some time to read
            if (!notif.classList.contains('paused')) {
                // Pause the animation
                notif.classList.add('paused');
                // Prevent to delete notification
                clearTimeout(animTimeOut);
            }
            // User has finished reading
            else {
                // launch a hide animation
                notif.classList.add('hide');
                notif.classList.remove('paused');
                // Remove from DOM jus after animation
                setTimeout(() => {
                    mainDiv.removeChild(notif);
                }, 1500);
            }
        });
    });
});
