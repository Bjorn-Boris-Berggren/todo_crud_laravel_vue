import Vue from 'vue';

Vue.config.errorHandler = (err, vm, info) => {
        alert("Unavailable to load page, please try to refresh page. If it does not work try later.");
};



