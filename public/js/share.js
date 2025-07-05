document.addEventListener('DOMContentLoaded', () => {
    const shareButton = document.getElementById('share-button');
    const cancelButton = document.getElementById('share-cancel-button');
    const popup = document.getElementById('share-popup');

    shareButton.addEventListener('click', () => {
        shareButton.style.display = 'none';
        cancelButton.style.display = 'none';

        html2canvas(popup, {
            backgroundColor: null, 
            useCORS: true 
        }).then(canvas => {
            shareButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';

            const image = canvas.toDataURL("image/png");
            const link = document.createElement('a');
            link.href = image;
            link.download = 'carbie-share.png';
            link.click();
        });
    });
});
