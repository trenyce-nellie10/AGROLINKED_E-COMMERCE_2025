document.addEventListener('DOMContentLoaded',()=>{
  const f=document.getElementById('loginForm'); if(!f) return;
  f.addEventListener('submit', async e=>{
    e.preventDefault();
    const fd=new FormData(f);
    const res = await fetch('actions/login.php',{method:'POST',body:fd});
    const j = await res.json(); document.getElementById('message').textContent = j.message || '';
    if (j.status==='success') setTimeout(()=>location.href='index.php',700);
  });
});
