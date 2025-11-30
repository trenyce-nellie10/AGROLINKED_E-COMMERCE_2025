document.addEventListener('DOMContentLoaded',()=>{
  const form=document.getElementById('registerForm'); if(!form) return;
  form.addEventListener('submit', async e=>{
    e.preventDefault();
    const fd=new FormData(form);
    const res = await fetch('../actions/register_action.php',{method:'POST',body:fd});
    const j = await res.json(); document.getElementById('message').textContent=j.message || '';
    if (j.status==='success') setTimeout(()=>location.href='login.php',700);
  });
});
