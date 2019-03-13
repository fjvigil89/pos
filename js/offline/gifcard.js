class Gifcard {
    constructor() {

    }
    async  get_giftcard_id(giftcard_number, deleted = false) {
       // let db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['giftcards'], 'readonly');
        const objectStore = transaction.objectStore('giftcards');
        let index = objectStore.index("giftcard_number");
        let gifcard = await index.get(giftcard_number);
        if (!deleted) {
            index = objectStore.index("[giftcard_number+deleted]");
            gifcard = await index.get([String(giftcard_number), "0"]);
        }
        if (gifcard != undefined) {
            return gifcard.giftcard_id;
        }
        return false;
    }
    async  delete_completely(giftcard_id)
	{
		//$this->db->where('giftcard_number', $giftcard_id);
		//$this->db->delete('giftcards');
    }
    async  get_giftcard_value( giftcard_number )
	{
        if ( await !this.exists(await this.get_giftcard_id(giftcard_number))){
            return 0;
        }
       // let db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['giftcards'], 'readonly');
        const objectStore = transaction.objectStore('giftcards');
        let index = objectStore.index("giftcard_number");
        let gifcard = await index.get(String(giftcard_number));          
        
        if (gifcard != undefined) {
            return gifcard.value;
        }return 0;
		
		
	}
    async  exists( giftcard_id )
	{
        //let  db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['giftcards'], 'readonly');
        const objectStore = transaction.objectStore('giftcards');        
        let  gifcard = await objectStore.get(String(giftcard_id));
        if(gifcard==undefined){
            return false;
        }
        else if(gifcard.deleted==1){
            return false;
        }else {
            return true;
        }
	}
}