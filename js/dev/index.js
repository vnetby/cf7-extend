import "../../css/dev/index.scss";


import { dom } from "vnet-dom";

import { recaptcha } from "./recaptcha";


const start = () => {
  let forms = dom.findAll('.wpcf7-form');
  if (!forms) return;
  forms.forEach(form => initRecaptchaForm(form));
}





const initRecaptchaForm = form => {
  dom.onClick('button[type="submit"], input[type="submit"]', e => {
    e.preventDefault();
    recaptcha(form).then(res => dom.dispatch(form, 'submit'));
  }, form);
}





start();