/* DriveEase — main.js v2.0 */
'use strict';

/* ── Navbar ─────────────────────────────── */
const nav = document.querySelector('.site-nav');
if(nav){
  const toggle = nav.querySelector('.nav-toggle');
  const links  = nav.querySelector('.nav-links');
  if(toggle && links){
    toggle.addEventListener('click',()=>{
      links.classList.toggle('open');
      toggle.innerHTML = links.classList.contains('open')
        ? '<i class="fa-solid fa-xmark"></i>'
        : '<i class="fa-solid fa-bars"></i>';
    });
    // Close on outside click
    document.addEventListener('click',e=>{
      if(!nav.contains(e.target)) links.classList.remove('open');
    });
  }
  window.addEventListener('scroll',()=>{
    nav.classList.toggle('scrolled', window.scrollY > 30);
  },{passive:true});
}

/* ── Scroll Reveal ─────────────────────── */
if('IntersectionObserver' in window){
  const els = document.querySelectorAll('.v-card,.stat-card,.feat-card,.booking-card,.step-card,.testi-card,.profile-card,.quick-action-card');
  const obs = new IntersectionObserver((entries)=>{
    entries.forEach((e,i)=>{
      if(e.isIntersecting){
        setTimeout(()=>{
          e.target.style.opacity='1';
          e.target.style.transform='translateY(0)';
        },i*60);
        obs.unobserve(e.target);
      }
    });
  },{threshold:.06,rootMargin:'0px 0px -30px 0px'});
  els.forEach(el=>{
    el.style.opacity='0';
    el.style.transform='translateY(24px)';
    el.style.transition='opacity .5s ease, transform .5s ease';
    obs.observe(el);
  });
}

/* ── Stat Counters ─────────────────────── */
if('IntersectionObserver' in window){
  const counters = document.querySelectorAll('.stat-value[data-target]');
  const cobs = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(!e.isIntersecting) return;
      const el = e.target;
      const target = parseInt(el.dataset.target,10)||0;
      const prefix = el.dataset.prefix||'';
      const suffix = el.dataset.suffix||'';
      if(!target){ el.textContent=prefix+'0'+suffix; return; }
      let t0=performance.now();
      const dur=1500;
      const tick=now=>{
        const p=Math.min((now-t0)/dur,1);
        const ease=1-Math.pow(1-p,3);
        el.textContent=prefix+Math.round(ease*target).toLocaleString('en-IN')+suffix;
        if(p<1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
      cobs.unobserve(el);
    });
  });
  counters.forEach(el=>cobs.observe(el));
}

/* ── Auto-dismiss alerts ───────────────── */
document.querySelectorAll('.alert,.auth-error,.auth-success').forEach(a=>{
  setTimeout(()=>{
    a.style.transition='opacity .5s, max-height .5s';
    a.style.opacity='0';
    setTimeout(()=>a.remove(),500);
  },5000);
});

/* ── Toast ─────────────────────────────── */
window.toast = (msg,type='info')=>{
  let c = document.getElementById('toast-container');
  if(!c){ c=document.createElement('div'); c.id='toast-container'; document.body.appendChild(c); }
  const t=document.createElement('div');
  t.className=`toast-msg ${type}`;
  t.textContent=msg;
  c.appendChild(t);
  setTimeout(()=>{
    t.style.opacity='0';
    t.style.transform='translateX(20px)';
    t.style.transition='all .3s ease';
    setTimeout(()=>t.remove(),300);
  },3500);
};

/* ── Category filter tabs ──────────────── */
document.querySelectorAll('.cat-tab[data-cat]').forEach(tab=>{
  tab.addEventListener('click',()=>{
    document.querySelectorAll('.cat-tab[data-cat]').forEach(t=>t.classList.remove('active'));
    tab.classList.add('active');
    const cat = tab.dataset.cat;
    document.querySelectorAll('.v-card-wrap').forEach(card=>{
      card.style.display=(cat==='all'||card.dataset.cat===cat)?'':'none';
    });
  });
});

/* ── Price Calculator ──────────────────── */
document.querySelectorAll('.booking-form').forEach(form=>{
  const startInput = form.querySelector('[name=start_date]');
  const endInput   = form.querySelector('[name=end_date]');
  const pricePerDay= parseInt(form.dataset.price)||0;
  const calc       = form.querySelector('.price-calc');
  const calcDays   = form.querySelector('.calc-days');
  const calcTotal  = form.querySelector('.calc-total');
  const update=()=>{
    if(!startInput.value||!endInput.value) return;
    const d1=new Date(startInput.value);
    const d2=new Date(endInput.value);
    const days=Math.max(1,Math.round((d2-d1)/86400000)+1);
    const total=days*pricePerDay;
    if(calc){
      calcDays.textContent=days+' day'+(days>1?'s':'');
      calcTotal.textContent='₹'+total.toLocaleString('en-IN');
      calc.classList.add('show');
    }
  };
  if(startInput) startInput.addEventListener('change',update);
  if(endInput)   endInput.addEventListener('change',update);
});

/* ── Star Rating ───────────────────────── */
document.querySelectorAll('.star-rating').forEach(wrap=>{
  const stars = wrap.querySelectorAll('.star');
  const input = document.getElementById('rating-value');
  stars.forEach((star,i)=>{
    star.addEventListener('click',()=>{
      if(input) input.value=i+1;
      stars.forEach((s,j)=>s.classList.toggle('active',j<=i));
    });
    star.addEventListener('mouseenter',()=>{
      stars.forEach((s,j)=>s.style.color=j<=i?'var(--orange)':'var(--t3)');
    });
    star.addEventListener('mouseleave',()=>{
      stars.forEach(s=>s.style.color='');
    });
  });
});

/* ── Active nav link ───────────────────── */
const path = window.location.pathname;
document.querySelectorAll('.nav-link-item').forEach(link=>{
  if(link.getAttribute('href') && path.includes(link.getAttribute('href').replace('../','').replace('./','')))
    link.classList.add('active');
});
