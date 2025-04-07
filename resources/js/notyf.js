import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

window.notyf = new Notyf({
    duration: 4000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'message',
            background: 'oklch(74.6% 0.16 232.661)', 
            icon: {
                className: 'material-icons custom-chat-icon',
                tagName: 'i',
                text: 'chat',
               
            }
        }
    ]
});

