
/**
 * @param {HTMLInputElement} input
 * @param {HTMLImageElement} img
 */
export function setup_preview(input, img) {
    input.addEventListener('input', e => {
        e.preventDefault();
        e.stopPropagation();

        let image = input.files[0] || null;
        if (image == null) {
            return false;
        }

        img.src = URL.createObjectURL(image);
    });
}

