<template>
<div class="container">
    <fieldset>
    <div v-for="item of fees.data" v-bind:key="item.id">
    <input type="checkbox" name="fee" v-bind:value="item.name" @click="handleClick()"> {{item.name}} KES. <strong>{{item.amount}}</strong><br>
    </div>
    </fieldset>
      <hr>
  <div class="form-row justify-content-center">
    <div class="form-group">
      <label>Amount</label>
    </div>
    <div class="form-group" style="margin-left: 3px;">
      <input type="text" v-model="amount" class="form-control form-control-sm" readonly="readonly" id="amount_dan" name="amount_DANA">
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
export default {
    data(){
        return{
            fees: {},
            amount: ''
        }
    },
    methods: {
        handleClick(){
            var checkboxes = document.getElementsByName("fee");
            var total = 0;
            for(var i = 0; i < checkboxes.length; i++)
            {
                if(checkboxes[i].checked)
                    total += parseFloat(checkboxes[i].value);

            }
              document.getElementById("amount").setAttribute("value", total);
            //   alert("Your total is !" + total);


        },
        loadFees(){
            axios.get('api/fee').then(({data}) => {
                this.fees = data
            })
        }
    },
        watch: {
        amount:function(val){
            this.$root.bladeValue = total;
        }
    },
    mounted() {
        this.loadFees();
        console.log('Example component mounted.')
    }
}
</script>
