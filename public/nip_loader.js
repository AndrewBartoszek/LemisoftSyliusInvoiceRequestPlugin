document.addEventListener("DOMContentLoaded", () => {
    var checkbox = document.getElementById('sylius_checkout_address_wantInvoice');
    var nipContainer = document.getElementById('js-nip-container');
    var isHiddenBillingAddress = checkbox.dataset.billing_hidden;
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            nipContainer.style.display = 'block';
            var differentAddressForBilling = document.getElementById('sylius_checkout_address_differentBillingAddress');
            if(false == differentAddressForBilling.checked){
                differentAddressForBilling.click();
            }
        } else {
            nipContainer.style.display = 'none';
        }
    });
    var getNipDataBtn = document.getElementById('js-get-data-to-billing-address-btn');
    getNipDataBtn.addEventListener("click", function(event) {
        event.preventDefault();

        var nip = document.getElementById('sylius_checkout_address_nip').value;
        var postObj = {
            nip: nip
        }
        var post = JSON.stringify(postObj);
        var url = this.dataset.url;
        var xhr = new XMLHttpRequest();

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
        xhr.send(post);
        xhr.onload = function () {
            if(xhr.status === 200) {

                var obj = JSON.parse(this.response);

                document.getElementById('sylius_checkout_address_billingAddress_firstName').value = obj.firstName;
                document.getElementById('sylius_checkout_address_billingAddress_lastName').value = obj.lastName;
                document.getElementById('sylius_checkout_address_nip').value = obj.nip;
                document.getElementById('sylius_checkout_address_billingAddress_postcode').value = obj.postCode;
                document.getElementById('sylius_checkout_address_billingAddress_city').value = obj.city;
                document.getElementById('sylius_checkout_address_billingAddress_street').value = obj.street;
                document.getElementById('sylius_checkout_address_billingAddress_company').value = obj.company;
                document.getElementById('sylius_checkout_address_billingAddress_buildingNumber').value = obj.buildingNumber;
                document.getElementById('sylius_checkout_address_billingAddress_apartmentNumber').value = obj.apartmentNumber;
            }
        }
    });































////  if (document.getElementById('sylius_checkout_address_wantInvoice').checked == true) {
////      console.log('aaaaaaaaaa');
////  }
//var checkbox = document.getElementById('sylius_checkout_address_wantInvoice')
//
//    var nipContainer = document.getElementById('js-nip-container');
//  checkbox.addEventListener('change', function() {
//    if (this.checked) {
//      console.log("Checkbox is checked..");
//    } else {
//      console.log("Checkbox is not checked..");
//    }
//  });



});
