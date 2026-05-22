function applyDominantBg(container) {
    const img = container.querySelector('.post-photo');
    if (!img) return;

    const apply = () => {
        try {
            const canvas = document.createElement('canvas');
            canvas.width = 10;
            canvas.height = 10;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, 10, 10);
            const data = ctx.getImageData(0, 0, 10, 10).data;

            let r = 0, g = 0, b = 0;
            for (let i = 0; i < data.length; i += 4) {
                r += data[i];
                g += data[i + 1];
                b += data[i + 2];
            }
            const count = data.length / 4;
            container.style.backgroundColor = `rgb(${r/count|0}, ${g/count|0}, ${b/count|0})`;
        } catch (e) {
            // CORS blocked — fall back to dark
            container.style.backgroundColor = '#1c1e21';
        }
    };

    img.complete ? apply() : img.addEventListener('load', apply);
}

document.querySelectorAll('.post-photos--1').forEach(applyDominantBg);
