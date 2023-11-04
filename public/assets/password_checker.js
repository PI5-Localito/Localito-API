/**
 * @param {HTMLFormElement} form
 */
export function setup_passwcheck(form) {
    form.addEventListener('submit', ev => {
        let passw = form.elements[form.id + '_password'].value;
        let confirm = form.elements[form.id + '__password_confirm'].value;

        if (passw !== confirm) {
            ev.preventDefault();
            ev.stopPropagation();
            console.log(passw, confirm);
            return false;
        }
    });
}
