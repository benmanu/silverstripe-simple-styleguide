import Vue from 'vue';
import './components/typography';
import AxeReport from './components/AxeReport';

new Vue({
  el: '#app',
  components: {
    'v-axe-report': AxeReport,
  },
});
