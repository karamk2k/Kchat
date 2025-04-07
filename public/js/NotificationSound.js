
const audio=new Audio('../sounds/notification-1-269296.mp3');

function playSound(){
    audio.play().catch(error => {
        console.log(error);
    });
}
