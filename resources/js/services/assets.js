import axios from 'axios';

class asset {

    /*********************************/
    constructor(asset_id = 0) {
        this.asset_id = asset_id;
    }

    /*********************************/
    get_mojodi(venue_id = 0) {
        return axios({
            url: '/api/ops/get_mojodi',
            data: {venue_id: venue_id ? venue_id : 'all'},
            method: 'post'
        }).then(res => {
            return res.data;
        }).catch(res => {
            console.log(res);
            return [];
        });
    }

    /*********************************/
    get_asset_name() {
        return 'sample asset name';
    }

    /*********************************/
}

export default asset;