import moment from 'moment';
import Helper from './Helper.js';
console.log('vue is:',process.env.NODE_ENV)
if (process.env.NODE_ENV === 'production') {
	console.log = function () {}; //
}
export default {
	formatCurrency: function(val = 0){
		var formatter = new Intl.NumberFormat('de-DE', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 0, // set to 0 to remove cents
		});
		val = formatter.format(val).replace('IDR','').trim();

		return val;
	},
	formatRp(x, decimal=2) {
		if(x==null) return '-';
		if(x==0) return '-';

		return parseFloat(x).toFixed(decimal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	},
	formatDate: function(dt = null, originalformat = 'DD/MM/YYYY', outputformat = 'YYYY-MM-DD'){
		if(dt){
			// let time = moment(new Date()).format('HH:mm:ss');
			dt = moment(dt, originalformat+' HH:mm:ss').format(outputformat)
		}
		return dt;
	},
	today: function(outputformat = 'DD/MM/YYYY'){
		return moment(new Date).format(outputformat);
	},
	startMonth: function(outputformat = 'DD/MM/YYYY'){
		return moment().startOf('month').format(outputformat);
	},
	lastMonth: function(outputformat = 'DD/MM/YYYY'){
		return moment().endOf('month').format(outputformat);
	},
	getAccessList: function(access = null, codeOnly=false){
		var acc = [];
		var obj = Object.keys(access).map((val)=>{
			if(parseInt(val)){
				if(codeOnly==false){
					acc.push(access[val]);
				}
				else{
					acc.push(access[val].code);
				}
			}
		});
		return acc;
	},
	convertBase64:function(file) {
		return new Promise((resolve, reject) => {
	        const fileReader = new FileReader();
	        fileReader.readAsDataURL(file);

	        fileReader.onload = () => {
	            resolve(fileReader.result);
	        };

	        fileReader.onerror = (error) => {
	            reject(error);
	        };
	    });
	},
	uploaded:async function(event) {
		const file = event.target.files[0];
    	const base64 = await this.convertBase64(file);
    	return {
    		name:file.name,
    		file:base64
    	};
	},
	closed(closingInv, itemDate) {
		let res = false;
		if(closingInv && itemDate) {
			res = moment(closingInv).isSameOrAfter(moment(itemDate), 'day');
		}
		return res;
	},
}