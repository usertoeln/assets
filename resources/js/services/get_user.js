import axios from 'axios';

class get_user {
    static get() {
        axios.get('/api/user')
            .then(response => {
                return response.data;
            }).catch(response => {
            return false;
        });
    }
}

export default get_user;