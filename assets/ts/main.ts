import SkeletonUI from "./Classes/skeletonUI";
import FlashMessage from "./Classes/flashMessage";
// import FlashMessage from "./Classes/flashMessage";
// import ConfirmBox from "./Classes/confirmBox";

export function main(){
    console.log('Initializing Skeleton...');
    //
    new SkeletonUI([
        {target: 'back', speed: '*(-1.5)'},
        {target: 'logo', speed: '*(0.750)'}
    ]);
    window['flash'] = new FlashMessage();

    // let languageSwitch = document.querySelector('#formSwitch');
    // const  languageSelectors  = Array.prototype.slice.call(document.querySelectorAll('.fooBar'), 0);
    // languageSelectors.forEach(link => {
    //     link.addEventListener('click',(e: Event)=>{
    //
    //         console.log('Are you Disturbing me....');
    //
    //     },false)
    // })


    // window['flash'].flashIt({
    //     type: 'warning',
    //     title: 'Image Error',
    //     message: 'The selected image is too small min 250x250',
    // })


    // console.log("Scrip Loaded")
    // let acc = document.getElementsByClassName("accordion");
    // let i;
    //
    // for (i = 0; i < acc.length; i++) {
    //     acc[i].addEventListener("click", function () {
    //         this.classList.toggle("active");
    //         let panel = this.nextElementSibling;
    //         if (panel.style.display === "block") {
    //             panel.style.display = "none";
    //         } else {
    //             panel.style.display = "block";
    //         }
    //     });
    // }


    let dropdown = document.getElementsByClassName("dropdown-btn");
    let i;
    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            let dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }



}

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}
    
   
