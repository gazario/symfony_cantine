/*var toastLiveExample = document.getElementById('liveToast')

if (toastLiveExample) {
    toastLiveExample.classList.add('show');
}*/

var toastLiveExample = document.querySelectorAll('.toast')
toastLiveExample.forEach((e)=>{
    if (e) {
        e.classList.add('show');
    }
})

