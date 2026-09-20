document.addEventListener('DOMContentLoaded',()=>{
  const loader=document.getElementById('site_loader');
  window.addEventListener('load',()=>setTimeout(()=>loader?.classList.add('is-hidden'),220),{once:true});
  setTimeout(()=>loader?.classList.add('is-hidden'),900);

  const header=document.querySelector('.site-header');
  const onScroll=()=>header?.classList.toggle('is-scrolled',window.scrollY>12);
  onScroll(); window.addEventListener('scroll',onScroll,{passive:true});

  const revealTargets=['.section-head','.service-card','.gallery-grid figure','.trust-item','.booking-banner','.review-card','.booking-form','.booking-side .card','.footer-grid>div','.reel-card','.before-after','.stat','.admin-card'];
  const nodes=[...document.querySelectorAll(revealTargets.join(','))];
  nodes.forEach((el,i)=>{el.classList.add('reveal');el.style.transitionDelay=`${Math.min((i%6)*65,325)}ms`;});
  if('IntersectionObserver' in window){
    const io=new IntersectionObserver((entries)=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('is-visible');io.unobserve(entry.target);}}),{threshold:.1,rootMargin:'0px 0px -30px 0px'});
    nodes.forEach(el=>io.observe(el));
  } else nodes.forEach(el=>el.classList.add('is-visible'));

  document.querySelectorAll('.btn').forEach(btn=>btn.addEventListener('click',e=>{
    const r=document.createElement('span');r.className='ripple';const rect=btn.getBoundingClientRect();const size=Math.max(rect.width,rect.height);
    r.style.width=r.style.height=`${size}px`;r.style.left=`${e.clientX-rect.left-size/2}px`;r.style.top=`${e.clientY-rect.top-size/2}px`;btn.appendChild(r);setTimeout(()=>r.remove(),700);
  }));

  const filterButtons=[...document.querySelectorAll('.gallery-filter')];
  const galleryItems=[...document.querySelectorAll('[data-gallery-category]')];
  filterButtons.forEach(btn=>btn.addEventListener('click',()=>{
    filterButtons.forEach(b=>b.classList.remove('is-active'));btn.classList.add('is-active');
    const filter=btn.dataset.filter;
    galleryItems.forEach(item=>item.classList.toggle('is-hidden',filter!=='all'&&item.dataset.galleryCategory!==filter));
  }));

  document.querySelectorAll('[data-before-after]').forEach(wrapper=>{
    const input=wrapper.querySelector('input[type=range]');const overlay=wrapper.querySelector('.before-after-overlay');const handle=wrapper.querySelector('.before-after-handle');
    const update=()=>{const v=input.value;overlay.style.width=`${v}%`;handle.style.left=`${v}%`;}; input.addEventListener('input',update);update();
  });

  const serviceInput=document.getElementById('service_id');
  const dateInput=document.getElementById('appointment_date');
  const timeInput=document.getElementById('appointment_time');
  const slots=document.getElementById('time_slots');
  const summaryService=document.getElementById('summary_service');
  const summaryDateTime=document.getElementById('summary_datetime');
  const serviceButtons=[...document.querySelectorAll('.service-choice')];
  const indicators=[...document.querySelectorAll('[data-step-indicator]')];
  const progress=document.getElementById('booking_progress_fill');

  function setIndicator(step){
    indicators.forEach((el,i)=>el.classList.toggle('is-active',i<step));
    if(progress) progress.style.width=`${Math.max(25,Math.min(100,step*25))}%`;
  }
  function selectedService(){return serviceButtons.find(b=>b.dataset.serviceId===serviceInput?.value);}
  function currentStep(){if(!serviceInput?.value)return 1;if(!dateInput?.value)return 2;if(!timeInput?.value)return 3;return 4;}
  function updateSummary(){
    const btn=selectedService();
    if(summaryService) summaryService.textContent=btn?btn.querySelector('strong').textContent:'لم يتم اختيار خدمة بعد';
    if(summaryDateTime){
      if(dateInput?.value&&timeInput?.value){const d=new Date(`${dateInput.value}T12:00:00`);summaryDateTime.textContent=`${d.toLocaleDateString('ar-SY',{weekday:'short',day:'numeric',month:'long'})} — ${timeInput.value}`;}
      else if(dateInput?.value) summaryDateTime.textContent='تم اختيار التاريخ، اختاري الوقت';
      else summaryDateTime.textContent='اختاري التاريخ والوقت';
    }
    setIndicator(currentStep());
  }
  async function loadSlots(){
    if(!dateInput||!serviceInput||!timeInput||!slots)return;
    timeInput.value=''; updateSummary();
    if(!dateInput.value||!serviceInput.value){slots.innerHTML='<div class="time-placeholder"><span>♡</span><p>اختاري الخدمة والتاريخ أولاً لنظهر لك الأوقات المتاحة.</p></div>';return;}
    slots.innerHTML='<div class="time-loading"><i></i><i></i><i></i></div>';
    try{
      const url=new URL(window.bookingAvailabilityUrl,window.location.origin);url.searchParams.set('date',dateInput.value);url.searchParams.set('service_id',serviceInput.value);
      const response=await fetch(url);const data=await response.json();
      if(!data.slots.length){slots.innerHTML='<div class="time-placeholder"><span>♡</span><p>لا توجد أوقات متاحة في هذا اليوم. جرّبي تاريخاً آخر.</p></div>';return;}
      slots.innerHTML=data.slots.map(x=>`<button class="time-slot" type="button" data-time="${x}">${x}</button>`).join('');
      const old=window.bookingOldTime;
      slots.querySelectorAll('.time-slot').forEach(btn=>{
        btn.addEventListener('click',()=>{slots.querySelectorAll('.time-slot').forEach(b=>b.classList.remove('is-selected'));btn.classList.add('is-selected');timeInput.value=btn.dataset.time;updateSummary();});
        if(old&&old.substring(0,5)===btn.dataset.time){btn.click();window.bookingOldTime=null;}
      });
    }catch(e){slots.innerHTML='<div class="time-placeholder"><span>!</span><p>تعذر تحميل الأوقات حالياً. حاولي مرة أخرى.</p></div>';}
  }
  serviceButtons.forEach(btn=>btn.addEventListener('click',()=>{
    serviceButtons.forEach(b=>{b.classList.remove('is-selected','just-selected');b.setAttribute('aria-pressed','false');});
    btn.classList.add('is-selected','just-selected');btn.setAttribute('aria-pressed','true');serviceInput.value=btn.dataset.serviceId;updateSummary();loadSlots();
  }));
  dateInput?.addEventListener('change',()=>{dateInput.classList.remove('choice-pop');void dateInput.offsetWidth;dateInput.classList.add('choice-pop');loadSlots();});
  if(serviceInput?.value&&dateInput?.value) loadSlots(); else updateSummary();
});

// Real reels: autoplay silently in view and open a focused player on tap.
document.addEventListener('DOMContentLoaded',()=>{
  const reelCards=[...document.querySelectorAll('.reel-video-card')];
  if('IntersectionObserver' in window){
    const reelObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{
      const video=entry.target.querySelector('video');
      if(!video)return;
      if(entry.isIntersecting){video.play().catch(()=>{});}else{video.pause();}
    }),{threshold:.55});
    reelCards.forEach(card=>reelObserver.observe(card));
  }
  const modal=document.getElementById('reel_modal');
  const modalVideo=document.getElementById('reel_modal_video');
  const close=()=>{if(!modal||!modalVideo)return;modal.classList.remove('is-open');modal.setAttribute('aria-hidden','true');modalVideo.pause();modalVideo.removeAttribute('src');modalVideo.load();document.body.style.overflow='';};
  reelCards.forEach(card=>card.addEventListener('click',()=>{if(!modal||!modalVideo)return;modalVideo.src=card.dataset.reelSrc;modal.classList.add('is-open');modal.setAttribute('aria-hidden','false');document.body.style.overflow='hidden';modalVideo.play().catch(()=>{});}));
  modal?.querySelector('.reel-modal-close')?.addEventListener('click',close);
  modal?.addEventListener('click',e=>{if(e.target===modal)close();});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&modal?.classList.contains('is-open'))close();});
});
