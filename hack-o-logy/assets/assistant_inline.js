(()=>{
 const msgs=document.getElementById('assistant-inline-messages');
 const input=document.getElementById('assistant-inline-input');
 const sendBtn=document.getElementById('assistant-inline-send');
 const voiceBtn=document.getElementById('assistant-voice-toggle');
 const SR = window.SpeechRecognition||window.webkitSpeechRecognition;
 let rec=null,active=false;
 function bubble(role,text){ const d=document.createElement('div'); d.className='assistant-msg '+role; d.textContent=text; msgs.appendChild(d); d.scrollIntoView({behavior:'smooth'}); }
 function tts(text){ if (!window.speechSynthesis) return; const u=new SpeechSynthesisUtterance(text); u.lang='en-US'; window.speechSynthesis.speak(u); }
 function renderSuggestions(items){ const grid=document.createElement('div'); grid.className='suggest-grid'; items.slice(0,6).forEach(b=>{ const card=document.createElement('div'); card.className='suggest-card'; const cover=b.cover_image?`<img class=\"cover-thumb\" src=\"${b.cover_image}\" alt=\"cover\">`:'<div class="cover-placeholder">BK</div>'; card.innerHTML=`<div class="d-flex align-items-center gap-2">${cover}<div><div class="title">${b.title}</div><div class="meta">${b.author} • ${b.category}</div><div class="meta">Available: ${b.available_copies}</div></div></div><div class="actions"><a class="btn btn-sm btn-outline-primary" href="?route=book&id=${b.id}">Open</a></div>`; grid.appendChild(card); }); msgs.appendChild(grid); }
 function send(){ const q=input.value.trim(); if(!q) return; bubble('user',q); input.value=''; fetch('?route=assistant_api',{method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({question:q})}).then(r=> r.ok ? r.json() : Promise.resolve({answer:'',suggestions:[]}) ).then(data=>{ const a=(data.answer && data.answer.trim())?data.answer:(Array.isArray(data.suggestions)&&data.suggestions.length?`Found ${data.suggestions.length} results`:'No matches found'); bubble('ai',a); tts(a); if (Array.isArray(data.suggestions)) renderSuggestions(data.suggestions); }).catch(()=>{ bubble('ai','Service unavailable'); }); }
 sendBtn.addEventListener('click',send);
 input.addEventListener('keydown',e=>{ if(e.key==='Enter') send(); });
 voiceBtn.addEventListener('click',()=>{ if(!SR) return; if(!rec){ rec=new SR(); rec.lang='en-US'; rec.continuous=false; rec.interimResults=false; rec.onresult=(ev)=>{ const t=ev.results[0][0].transcript; input.value=t; send(); }; rec.onend=()=>{ active=false; voiceBtn.classList.remove('active'); voiceBtn.textContent='Voice'; }; }
 if(active){ rec.stop(); active=false; voiceBtn.classList.remove('active'); voiceBtn.textContent='Voice'; } else { rec.start(); active=true; voiceBtn.classList.add('active'); voiceBtn.textContent='Listening'; }
 });
 // quick chips
 const chips=document.querySelectorAll('.chip[data-action]');
 chips.forEach(ch=> ch.addEventListener('click',()=>{
  const a=ch.getAttribute('data-action');
  if (a==='attach') {
   const inp=document.createElement('input'); inp.type='file'; inp.accept='image/*,.pdf,.txt'; inp.onchange=()=>{ if (inp.files && inp.files[0]) bubble('user', 'Attached: '+inp.files[0].name); }; inp.click();
  } else if (a==='search') {
   input.value='Find books on '; input.focus();
  } else if (a==='study') {
   input.value='Recommend study materials for '; input.focus();
  }
 }));
})();
