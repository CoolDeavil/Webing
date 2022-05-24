// import {parse} from "postcss-scss";

export default class SelectBOX {
    private selectElement: HTMLSelectElement;
    private readonly selectElementType: string;
    private readonly wrapperDiv: HTMLDivElement;
    private needle: HTMLInputElement;
    private toggleDropdown: HTMLDivElement;
    private dropPanel: HTMLDivElement;
    private uiList: HTMLUListElement;
    private tagWrapper: HTMLDivElement;
    private isInitializing: boolean;
    private selectionUI: number;

    constructor(selectElement: HTMLSelectElement){
        this.selectElement = selectElement;
        this.selectElementType = selectElement.type;
        this.wrapperDiv = document.createElement('div');
        this.wrapperDiv.className = 'dropdownSelect';
        this.wrapperDiv.innerHTML = SelectBOX.template(this.selectElementType);
        this.selectElement.insertAdjacentElement('beforebegin', this.wrapperDiv);
        this.selectElement.classList.add('offView');
        this.isInitializing = true;
        this.selectionUI = -1;
        this.bootstrap();
    }
    bootstrap(){
        setTimeout(()=>{
            this.needle = this.wrapperDiv.querySelector('.needle') ;
            this.dropPanel = this.wrapperDiv.querySelector('.contents');
            this.needle.addEventListener('keyup', this.keyboardHandler.bind(this), false);
            this.toggleDropdown = this.wrapperDiv.querySelector('.dropToggle');
            this.uiList = this.wrapperDiv.querySelector('ul');
            this.tagWrapper  = this.wrapperDiv.querySelector('.tagWrapper');
            document.addEventListener('click', this.closeDropPanel.bind(this))
            this.toggleDropdown.onclick = (e)=>{
                this.dropPanel.classList.toggle('show');
                this.needle.focus();
            }
            this.makeUIList();
            this.setUserTags();
        }, 100)
    }
    makeUIList(){
        this.uiList.innerHTML = '';
        for(let i=0;i< this.selectElement.options.length;i++){
            let li = document.createElement('li');
            li.dataset.index = i.toString();
            if(parseInt(this.selectElement.options[i].value) < 1){
                li.style.display = 'none';
            }
            li.innerHTML= `<img alt="" class="icon" src="${this.selectElement.options[i].dataset.image}">&ensp;<span>${this.selectElement.options[i].innerText}</span>`;
            if(this.selectElement.options[i].selected){
                li.classList.add('selected');
            }
            li.addEventListener('click',this.listClickHandler.bind(this),false) ;
            li.addEventListener('mouseover',this.listHoverHandler.bind(this),false) ;
            this.uiList.appendChild(li);
        }
        if( this.isInitializing != true){
            this.emitChangeEvent()
        }
        this.isInitializing = false;
    }
    clearSelect(index:number = null) : void {
        if(index !== null){
            this.selectElement.options[index].selected = false;
            this.uiList.querySelectorAll('LI')[index].classList.remove('selected');
            return;
        }
        for(let index=0;index<this.selectElement.options.length;index++){
            this.uiList.querySelectorAll('LI')[index].classList.remove('selected')
        }
    }
    closeDropPanel(evt: MouseEvent){
        let temp = SelectBOX.findParentElement(evt.target,'dropdownSelect');
        if(temp !== this.wrapperDiv) {
            this.dropPanel.classList.remove('show')
        }
    }
    listClickHandler(event : MouseEvent) : void {
        event.preventDefault();
        let index: number;
        if((<HTMLElement>event.target).nodeName !== 'LI') {
            index = parseInt(((<HTMLElement>event.target).parentNode as HTMLElement).dataset.index );
        }
        else {
            index = parseInt(((<HTMLElement>event.target) as HTMLElement).dataset.index );
        }
        switch (this.selectElementType){
            case 'select-one':
                this.clearUISelection();
                if(this.selectElement.selectedIndex === index ){
                    this.uiList.querySelectorAll('LI')[index].classList.remove('selected');
                    this.selectElement.selectedIndex = 0;
                }else {
                    this.uiList.querySelectorAll('LI')[index].classList.add('selected');
                    this.selectElement.selectedIndex = index ;
                }
                this.dropPanel.classList.remove('show');
                break;
            case 'select-multiple':
                if(this.selectElement.options[index].selected ){
                    this.selectElement.options[index].selected = false;
                    this.uiList.querySelectorAll('LI')[index].classList.remove('selected');
                }else{
                    this.selectElement.options[index].selected = true;
                    this.uiList.querySelectorAll('LI')[index].classList.add('selected');
                }
                if (event.ctrlKey){
                    this.dropPanel.classList.add('show');
                }else{
                    this.dropPanel.classList.remove('show');
                }
                break;
            default:
                break;
        }
        this.emitChangeEvent();
        this.setUserTags();
    }
    listHoverHandler(evt: MouseEvent){
        this.selectionUI = parseInt((<HTMLElement>evt.target).dataset.index);
        this.clearUIHover();
        this.uiList.querySelectorAll('li')[this.selectionUI].classList.add('hovered')
    }
    clearUIHover(){
        this.uiList.querySelectorAll('LI').forEach((opt)=>{
            opt.classList.remove('hovered');
        });
    }
    clearUISelection(){
        this.uiList.querySelectorAll('LI').forEach((opt)=>{
            opt.classList.remove('selected');
        });
    }
    setUserTags(){
        // @ts-ignore
        let options = Array.from(this.selectElement.querySelectorAll('option'));
        this.needle.value='';
        let prompt: boolean; prompt = false;
        let tags:  any[];tags = [];
        let count: number; count = 0;

        for(let c=0; c<this.selectElement.options.length; c++){
            if(this.selectElement.options[c].selected){
                count++;
            }
        }
        if( count>1 && options[0].value === '0' &&  options[0].selected ){
           //options.shift();
           this.isInitializing=true;
           this.selectElement.options[0].selected = false;
        }
        if(this.getSelectValues(this.selectElement).length > 0){
            options.forEach((opt, i )=>{
                opt.dataset.index = i.toString();
                if(opt.selected){
                    tags.push({
                        title: opt.innerText,
                        index: opt.dataset.index,
                        image: opt.dataset.image
                    });
                }
            });
        }else if(this.selectElement.options[0].value === '0'){
            prompt = true;
            tags.push({
                title: this.selectElement.options[0].innerText,
                index: 0,
                image: './images/placeholder.png'
            });
        } else {
            prompt = true;
            tags.push({
                title: 'Click to select',
                index: 0,
                image: ''
            });
        }
        let newTags = this.makeNewTags(tags);
        this.tagRemoveAll()
        newTags.forEach((nt)=>{
            nt.addEventListener('click',this.tagClickHandler.bind(this), false) ;
            this.tagWrapper.querySelector('.tagNeedle').insertAdjacentElement('beforebegin', nt);
            // this.needle.focus();
        })
        if(prompt){
            this.tagWrapper.querySelectorAll('.tag')[0].classList.add('prompt');
        }
        this.needle.placeholder = "";
        this.needle.focus();
    }
    makeNewTags(options){
        let tags = [];
        options.forEach((opt,i)=>{
            let el = document.createElement('div');
            el.className = 'tag';
            if(this.selectElement.options[i].value === '0' && this.selectElement.options[i].selected){
                el.classList.add('prompt');
            }
            el.dataset.index = opt.index;
            el.innerHTML = `<span class=""><img src="${opt.image}" alt="" class="icon">&ensp;${opt.title}</span>&ensp;<i class="fa fa-times"></i>`;
            tags.push(el)
        })
        return tags;
    }
    tagRemoveAll(){
        let tags: any = [];
        if(this.tagWrapper.querySelectorAll('.tag')){
            tags = this.tagWrapper.querySelectorAll('.tag');
            tags.forEach((t)=>{
                t.parentNode.removeChild(t);
            })
        }
    }
    tagClickHandler(e){

        if(e.target.classList.contains('fa')){
            this.selectElement.options[e.target.parentNode.dataset.index].selected = false;
            this.clearSelect(e.target.parentNode.dataset.index);
            fadeOut(e.target.parentNode);
            setTimeout(()=>{
                e.target.parentNode.parentNode.removeChild(e.target.parentNode);
                this.setUserTags();
            }, 100);
            this.emitChangeEvent();
            return;
        }  else if( e.target.classList.contains('prompt')) {
            this.dropPanel.classList.add('show');
        }else {
            this.dropPanel.classList.add('show');
        }
        this.needle.focus();

        function fadeOut(el){
            el.style.opacity = 1;
            (function fade() {
                if ((el.style.opacity -= 0.1) < 0) {
                    el.style.display = "none";
                } else {
                    requestAnimationFrame(fade);
                }
            })();
        }
    }
    keyboardHandler(event: KeyboardEvent){
        switch (event.key) {
            case ' ':
                if(this.dropPanel.classList.contains('show')){
                    let evt : Event = document.createEvent('HTMLEvents');
                    evt.initEvent('click', true, false);
                    evt['ctrlKey'] = true;
                    this.uiList.querySelectorAll('li')[this.selectionUI].dispatchEvent(evt);
                }
                break
            case 'ArrowUp':
                if(!this.dropPanel.classList.contains('show')){
                    this.dropPanel.classList.add('show');
                    return;
                }
                if(this.selectionUI - 1 >= 0){
                    this.clearUIHover();
                    this.selectionUI--;
                    this.uiList.querySelectorAll('li')[this.selectionUI].classList.add('hovered')
                }else{
                    this.clearUIHover();
                    this.selectionUI =this.uiList.querySelectorAll('li').length -1;
                    this.uiList.querySelectorAll('li')[this.selectionUI].classList.add('hovered')
                }
                break;
            case 'ArrowDown':
                if(!this.dropPanel.classList.contains('show')){
                    this.dropPanel.classList.add('show');
                    return;
                }
                if(this.selectionUI + 1 < this.uiList.querySelectorAll('li').length){
                    this.clearUIHover();
                    this.selectionUI++;
                    this.uiList.querySelectorAll('li')[this.selectionUI].classList.add('hovered')
                }else{
                    this.clearUIHover();
                    this.selectionUI = 0;
                    this.uiList.querySelectorAll('li')[this.selectionUI].classList.add('hovered')
                }
                break;
            case 'Escape':
                this.dropPanel.classList.remove('show');
                break;
            case 'Enter':
                break;
            case 'Control':
                console.log("Control is pressed")
                break;
            case 'Backspace':
            case 'Delete':
                break;
            default:
            break;
        }
    }
    emitChangeEvent(){
        let event = document.createEvent('HTMLEvents');
        event.initEvent('change', true, false);
        this.selectElement.dispatchEvent(event);
    }
    getSelectValues( select ) {
        let result = [];
        const options = select && select.options;
        let opt;

        for (let i=0, iLen=options.length; i<iLen; i++) {
            opt = options[i];

            if (opt.selected) {
                result.push(opt.value || opt.text);
            }
        }
        return result;
    }
    static template(type : string ){
        return `<div class="tagWrapper">
                <div class="dropToggle"></div>
                <div class="tagNeedle">
                        <input type="text" class="needle ${type === 'select-multiple'?'needleMultiple':''}" aria-label="">
                </div>
                </div>
                <div class="contents">
                    <ul class="uiList">
                    </ul>
                </div>`
    }
    static findParentElement(el, eClass) {
        while (el.parentNode) {
            el = el.parentNode;
            if (el.className === eClass){
                return el;
            }
        }
        return null;
    }
}
