
export default class SkeletonUI {
    private appHero: HTMLElement;
    private appNav: HTMLElement;
    private sideNav: HTMLElement;
    private fab: HTMLElement;
    private toggle: HTMLElement;
    private formSwitch: HTMLFormElement;
    private appContent: HTMLElement;
    private languageValue: HTMLInputElement;
    public langSwitch: NodeListOf<HTMLElement>;
    public heroParallaxList: NodeListOf<HTMLElement>;
    private windowMediaQuery: string;
    public stickyActive: boolean;
    private windowY: number;
    private windowX: number;
    private heroHeight: number;
    public parallax: Boolean;
    public parallaxElementList: any[];

    constructor(parallaxElements: any) {
        this.appHero = document.querySelector(".appHero");
        this.appContent = document.querySelector(".appContent");
        this.appNav = document.querySelector(".appNavigation");
        this.sideNav = document.querySelector(".appSideNavOverlay");
        this.fab = document.querySelector(".cFab");
        this.toggle = document.querySelector(".js_hamburger");        // TODO write functionality
        this.langSwitch = document.querySelectorAll(".language");
        this.formSwitch = document.querySelector("#formSwitch");
        this.languageValue = document.querySelector('input[name="language"]');
        this.parallaxElementList = parallaxElements;
        this.stickyActive = true;
        this.parallax = true;

        if (!this.checkParallaxParams()){
            this.parallax = false;
            console.log("[#] " , this.parallax)
        }

        this.setEventHandlers();
        console.log('SkeletonUI V0.0.2')
    }
    checkParallaxParams(){
        this.heroParallaxList = this.appHero.querySelectorAll('.parallax');
        if(this.parallaxElementList.length !== this.heroParallaxList.length){
            this.parallax = false;
            return this.parallax
        }
        return true;
    } 
    setEventHandlers() : void {
        window.addEventListener('scroll',this.onWindowScroll.bind(this),false);
        window.addEventListener('resize',this.onWindowResize.bind(this),false);
        this.fab.onclick = (e)=>{
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            this.fab.classList.add('pressed');
            setTimeout(()=>{
                this.fab.classList.remove('pressed');
            }, 500);
        }
        this.toggle.onclick = ()=>{
            console.log("Toggle Side Navigation");
            this.sideNav.classList.toggle('isShow');
            this.sideNav.querySelector('.appSideNav').classList.toggle('showNavAnim');
            // TODO Add transition end to close panel
        };
        [].forEach.call(this.langSwitch, (l:HTMLElement)=>{
            l.onclick = (e:MouseEvent)=>{
                e.preventDefault();
                // @ts-ignore
                this.languageValue.value = e.target.classList[1].replace(/ /g,'');
                this.formSwitch.submit();
            }
        })
    }
    onWindowScroll() : void {
        this.windowY = Math.round( window.pageYOffset
            || document.documentElement.scrollTop
            || document.body.scrollTop || 0);
        this.windowX = Math.round(window.pageXOffset);
        this.handleUIScroll(this.windowX, this.windowY);
    }
    onWindowResize() : void {
        this.windowY = Math.round(window.pageYOffset);
        this.windowX = Math.round(window.pageXOffset);
        this.windowMediaQuery = SkeletonUI.getCurrentMQuery();
        this.handleUIResize();
    }
    handleUIScroll(curX: number, curY: number) {
        this.heroHeight = this.appHero.offsetHeight;
        if(this.stickyActive){
            if (curY >= this.heroHeight) {
                this.appNav.classList.add('isSticky');
                this.appContent.classList.add('isSticky');
                this.fab.classList.add('isShow');
            } else {
                this.appNav.classList.remove('isSticky');
                this.appContent.classList.remove('isSticky');
                this.fab.classList.remove('isShow');
            }
        }
        if(this.parallax){
            if(!this.appNav.classList.contains("isSticky") && this.parallax === true ){
                [].forEach.call(this.parallaxElementList, (p: { target: string; speed: number; }): void =>{
                    let pElement: HTMLElement = this.appHero.querySelector("." + p.target);
                    pElement.style.transform = "translate3D("+(this.windowX)+"px, "+eval(this.windowY.toString()+p.speed)+"px, 0)"
                })
            }
        }
    }
    handleUIResize() : void  {
        if(!this.stickyActive){ return }
        this.heroHeight = this.appHero.offsetHeight;
        if (this.windowMediaQuery === 'mobile' || this.windowMediaQuery === 'mobile_xs') {
            this.appNav.classList.add('isSticky');
            this.appContent.classList.add('isSticky');
        } else {
            this.handleUIScroll(this.windowX, this.windowY);
        }
    }
    static getCurrentMQuery() : string {
        return window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/"/g, '');
    }
}
