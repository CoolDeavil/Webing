import SelectBOX from "../Classes/selectBox";

export function main(){
	console.log('User Dashboard Manager');
    let dropSelect : SelectBOX;

    dropSelect = new SelectBOX(
        document.querySelector('#languageSelector')
    );
}


if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}