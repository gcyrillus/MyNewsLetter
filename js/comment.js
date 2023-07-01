const validateEmail = (email) => {
  return email.match(
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
};
const mail = document.querySelector('#id_mail');
const validate = () => {
  const result = document.querySelector('#subscribeME');
  const email = document.querySelector('#id_mail').value;
  if(validateEmail(email)){
     result.style.display='flex';
  } else{
     result.style.display='none';
  }
  return false;
}
mail.addEventListener("blur",validate);