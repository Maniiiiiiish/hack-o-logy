(()=>{
 const fab = document.createElement('button');
 fab.className = 'assistant-fab btn btn-primary';
 fab.textContent = 'Ask AI';
 document.body.appendChild(fab);
 const panel = document.createElement('div');
 panel.className = 'assistant-panel card';
 panel.innerHTML = "<div class='card-body'><div class='d-flex justify-content-between align-items-center'><div class='fw-bold'>Assistant</div><button class='btn btn-sm btn-outline-secondary' id='assistant-close'>Close</button></div><div id='assistant-messages' class='mt-2'></div><div class='input-group mt-3'><input id='assistant-input' type='text' class='form-control' placeholder='Speak or type...'><button class='btn btn-outline-primary' id='assistant-voice'>Voice</button><button class='btn btn-primary' id='assistant-send'>Send</button></div></div>";
 document.body.appendChild(panel);
 let rec=null,recActive=false;
 const SR = window.SpeechRecognition||window.webkitSpeechRecognition;
 function append(role,text){ const m=document.createElement('div'); m.className='assistant-msg '+role; m.textContent=text; document.getElementById('assistant-messages').appendChild(m); m.scrollIntoView({behavior:'smooth'}); }
 function tts(text){ if (!window.speechSynthesis) return; const u=new SpeechSynthesisUtterance(text); u.lang='en-US'; window.speechSynthesis.speak(u); }
 function send(){ const input=document.getElementById('assistant-input'); const q=input.value.trim(); if(!q) return; append('user',q); input.value=''; fetch('?route=assistant_api',{ method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({question:q}) }).then(r=> r.ok ? r.json() : Promise.resolve({answer:'',suggestions:[]}) ).then(data=>{ const a=(data.answer && data.answer.trim())?data.answer:(Array.isArray(data.suggestions)&&data.suggestions.length?`Found ${data.suggestions.length} results`:'No matches found'); append('ai',a); tts(a); if (Array.isArray(data.suggestions)) { data.suggestions.slice(0,5).forEach(b=>{ const link=document.createElement('a'); link.href='?route=book&id='+b.id; link.className='assistant-suggestion'; link.textContent=b.title+' • '+b.author; document.getElementById('assistant-messages').appendChild(link); }); } }).catch(()=>{ append('ai','Service unavailable'); }); }
 fab.addEventListener('click',()=>{ panel.classList.add('open'); });
 panel.querySelector('#assistant-close').addEventListener('click',()=>{ panel.classList.remove('open'); });
 panel.querySelector('#assistant-send').addEventListener('click',send);
 panel.querySelector('#assistant-input').addEventListener('keydown',e=>{ if(e.key==='Enter') send(); });
 panel.querySelector('#assistant-voice').addEventListener('click',()=>{ if (!SR) return; if (!rec) { rec=new SR(); rec.lang='en-US'; rec.continuous=false; rec.interimResults=false; rec.onresult=(ev)=>{ const t=ev.results[0][0].transcript; document.getElementById('assistant-input').value=t; }; rec.onend=()=>{ recActive=false; panel.querySelector('#assistant-voice').textContent='Voice'; }; }
 if(recActive){ rec.stop(); recActive=false; panel.querySelector('#assistant-voice').textContent='Voice'; } else { rec.start(); recActive=true; panel.querySelector('#assistant-voice').textContent='Listening'; } });
})();
