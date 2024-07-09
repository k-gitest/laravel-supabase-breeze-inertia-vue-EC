<script setup lang="ts">
  import { ref, onMounted } from 'vue';
  import { loadStripe, Stripe, StripeElements, StripeCardElement, StripeCardNumberElement, StripeCardExpiryElement, StripeCardCvcElement } from '@stripe/stripe-js';
  import { Head, useForm, usePage, router } from '@inertiajs/vue3';
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import EcLayout from "@/Layouts/EcLayout.vue"

  const stripe = ref<Stripe | null>(null);
  const elements = ref<StripeElements | null>(null);
  const cardNumber = ref<StripeCardNumberElement | null>(null);
  const cardExpiry = ref<StripeCardExpiryElement | null>(null);
  const cardCvc = ref<StripeCardCvcElement | null>(null);
  const clientSecret = ref<string | null>(null);
  const error_message = ref<string | undefined>("");
  const stripekey = import.meta.env.VITE_STRIPE_KEY
  
  const { props } = usePage()
  clientSecret.value = props.clientSecret
  
  onMounted(async() => {
    const stripeInstance = await loadStripe(stripekey);
    if (stripeInstance) {
      stripe.value = stripeInstance;
      elements.value = stripe.value.elements();
      
      cardNumber.value = elements.value.create('cardNumber');
      cardNumber.value.mount('#card-number');

      cardExpiry.value = elements.value.create('cardExpiry')
      cardExpiry.value.mount('#card-expiry');

      cardCvc.value = elements.value.create('cardCvc')
      cardCvc.value.mount('#card-cvc');
    } else {
      console.error('Stripe failed to load.');
    }
  })

  const submit = async() => {
    if (stripe.value && clientSecret.value && cardNumber.value && cardExpiry.value && cardCvc.value) {
      const { paymentIntent, error } = await stripe.value.confirmCardPayment(clientSecret.value, {
        payment_method: {
          card: cardNumber.value,
          billing_details: {
            email: "jenny@example.com",
            name: "Jenny Rosen",
            phone: "+335555555555",
            address: {
                line1: 'tokyo, minato',
                line2: '',              
                city: 'Tokyo',          
                state: 'Minato',        
                postal_code: '123-4567',
                country: 'JP',          
            },
          },
        },
      })
      
      if (error) {
        console.error(error);
        error_message.value = error.message;
      } else if (paymentIntent && paymentIntent.status === 'succeeded') {
        router.visit('/order', {
          method: 'get',
          preserveScroll: true,
          preserveState: true,
          onFinish: () => {
            router.reload();
          }
        })
      }
    }
  }

</script>

<template>
  <Head title="Payment" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Payment</h2>
    </template>
    <EcLayout>
      <div class="w-full border p-2">
        <div class="flex flex-col gap-2">
          <span>合計金額（税抜き）：{{ props.totalPrice.total_price_excluding_tax }} 円</span>
          <span>決済金額（税込み）：{{ props.totalPrice.total_price_including_tax }} 円</span>
        </div>
        <div v-if="error_message" class="text-red-600">
          {{ error_message }}
        </div>
        <form @submit.prevent="submit">
          <div class="flex flex-col my-5 gap-2">
            <label for="card-number" class="text-sm font-medium text-gray-700">
              カード番号：
              <div id="card-number" class="border p-2 w-full" />
            </label>
            <label for="card-expiry" class="text-sm font-medium text-gray-700">
              有効期限：
              <div id="card-expiry" class="border p-2 w-full" />
            </label>
            <label for="card-cvc" class="text-sm font-medium text-gray-700">
              セキュリティコード：
              <div id="card-cvc" class="border p-2 w-full" />
            </label>
          </div>
          <button type="submit" class="btn">決済する</button>
        </form>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>