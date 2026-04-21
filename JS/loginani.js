/* Gsap pra animar o login */
const tl = gsap.timeline({});


/* Anima o centro */
tl.fromTo(
    '.login_content',
    {
      y: -800, 
      scaleX: .2, 
      scaleY: .5,
      opacity: 0
    },
    {
      y: 0, 
      scaleX: .2, 
      scaleY: .5,
      opacity: 1, 
      duration: 1.5, 
      ease: 'power3.out'
   }
)


/* Expandi vertical*/
tl.to(
   '.login_content', 
   {
      scaleY: 1,
      duration: .6, 
      ease: 'power3.out'
   }, '-=0.3'
)

/* Expandi horizontal */
tl.to(
   '.login_content', 
   {
      scaleX: 1,
      duration: .7, 
      ease: 'power3.out'
   }, '-=0.2'
)

/* Anima o fundo */
tl.to(
   '.login_img',
   {
      scale: 1.08, 
      duration: 5, 
      ease: 'power1.inOut', 
      repeat: -1, 
      yoyo: true, 
      transformOrigin: 'center center'
   }
)

/* Anima o form */
gsap.defaults({opacity: 0, y: -60, ease: 'power2.out', duration: 1.2})
gsap.from('.login_title',{delay: 2.5})
gsap.from('.login_form > *',{delay: 2.7, stagger: .2})
gsap.from('.login_img',{y: 0, x: 100, delay: 3.2, ease: 'elastic.out(1,0.6)'})