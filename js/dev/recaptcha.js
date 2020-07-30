import { dom } from "vnet-dom";



export const recaptcha = form => {
  return new Promise((resolve, reject) => {
    if (!dom.document.head.querySelector('.google-captcha')) {
      loadCaptcha().then(() => appendRecaptchaResponse(resolve, reject, form));
    } else {
      appendRecaptchaResponse(resolve, reject, form);
    }
  });
}




const loadCaptcha = () => {
  return new Promise(promiseLoadCaptch);
}




const promiseLoadCaptch = (resolve, reject) => {
  let script = dom.document.createElement('script');
  script.onload = (e) => {
    resolve();
  }
  script.src = `${cfextend.recaptchaSrc}`;
  script.className = 'google-captcha';
  dom.document.head.appendChild(script);
}




const appendRecaptchaResponse = (resolve, reject, form) => {
  disableSubmitBtn(form);
  grecaptcha.ready(() => {
    grecaptcha.execute(cfextend.recaptchaSiteKey, { action: form.dataset.captchaAction || 'submit' }).then(token => {
      let input = dom.findFirst('.g-recaptcha-response', form);
      if (!input) {
        input = dom.create('input', { type: 'hidden', name: 'g-recaptcha-response', className: 'g-recaptcha-response' });
        form.appendChild(input);
      }
      input.value = token;
      unDisableSubmitBtn(form);
      resolve();
    });
  })
}





const disableSubmitBtn = (form) => {
  dom.addClass('[type="submit"]', 'disabled', form);
}


const unDisableSubmitBtn = (form) => {
  dom.removeClass('[type="submit"]', 'disabled', form);
}