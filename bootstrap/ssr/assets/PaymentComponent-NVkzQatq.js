import { defineComponent, ref, onMounted, unref, withCtx, createVNode, toDisplayString, openBlock, createBlock, createCommentVNode, withModifiers, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { loadStripe } from "@stripe/stripe-js";
import { usePage, Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "PaymentComponent",
  __ssrInlineRender: true,
  setup(__props) {
    const stripe = ref(null);
    const elements = ref(null);
    const cardNumber = ref(null);
    const cardExpiry = ref(null);
    const cardCvc = ref(null);
    const clientSecret = ref(null);
    const error_message = ref("");
    const stripekey = "pk_test_51PHL8VRuumzZmk25YztMU2a7vBtElLMZSf1ZXbQJMKhhVWeun88s20Gp93Vxxj3BUC9bccnIXQMLVuqfW2k6b16A00pY0aHdFj";
    const { props } = usePage();
    clientSecret.value = props.clientSecret;
    onMounted(async () => {
      const stripeInstance = await loadStripe(stripekey);
      if (stripeInstance) {
        stripe.value = stripeInstance;
        elements.value = stripe.value.elements();
        cardNumber.value = elements.value.create("cardNumber");
        cardNumber.value.mount("#card-number");
        cardExpiry.value = elements.value.create("cardExpiry");
        cardExpiry.value.mount("#card-expiry");
        cardCvc.value = elements.value.create("cardCvc");
        cardCvc.value.mount("#card-cvc");
      } else {
        console.error("Stripe failed to load.");
      }
    });
    const submit = async () => {
      if (stripe.value && clientSecret.value && cardNumber.value && cardExpiry.value && cardCvc.value) {
        const { error } = await stripe.value.confirmCardPayment(clientSecret.value, {
          payment_method: {
            card: cardNumber.value,
            billing_details: {
              email: "jenny@example.com",
              name: "Jenny Rosen",
              phone: "+335555555555",
              address: {
                line1: "tokyo, minato",
                line2: "",
                city: "Tokyo",
                state: "Minato",
                postal_code: "123-4567",
                country: "JP"
              }
            }
          }
        });
        if (error) {
          console.error(error);
          error_message.value = error.message;
        } else {
          router.visit("/cart/index", {
            method: "get",
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
              router.reload();
            }
          });
        }
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Payment" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Payment</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Payment")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="w-full border p-2"${_scopeId2}><div class="flex flex-col gap-2"${_scopeId2}><span${_scopeId2}>合計金額（税抜き）：${ssrInterpolate(unref(props).totalPrice.total_price_excluding_tax)} 円</span><span${_scopeId2}>決済金額（税込み）：${ssrInterpolate(unref(props).totalPrice.total_price_including_tax)} 円</span></div>`);
                  if (error_message.value) {
                    _push3(`<div class="text-red-600"${_scopeId2}>${ssrInterpolate(error_message.value)}</div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`<form${_scopeId2}><div class="flex flex-col my-5 gap-2"${_scopeId2}><label for="card-number" class="text-sm font-medium text-gray-700"${_scopeId2}> カード番号： <div id="card-number" class="border p-2 w-full"${_scopeId2}></div></label><label for="card-expiry" class="text-sm font-medium text-gray-700"${_scopeId2}> 有効期限： <div id="card-expiry" class="border p-2 w-full"${_scopeId2}></div></label><label for="card-cvc" class="text-sm font-medium text-gray-700"${_scopeId2}> セキュリティコード： <div id="card-cvc" class="border p-2 w-full"${_scopeId2}></div></label></div><button type="submit" class="btn"${_scopeId2}>決済する</button></form></div>`);
                } else {
                  return [
                    createVNode("div", { class: "w-full border p-2" }, [
                      createVNode("div", { class: "flex flex-col gap-2" }, [
                        createVNode("span", null, "合計金額（税抜き）：" + toDisplayString(unref(props).totalPrice.total_price_excluding_tax) + " 円", 1),
                        createVNode("span", null, "決済金額（税込み）：" + toDisplayString(unref(props).totalPrice.total_price_including_tax) + " 円", 1)
                      ]),
                      error_message.value ? (openBlock(), createBlock("div", {
                        key: 0,
                        class: "text-red-600"
                      }, toDisplayString(error_message.value), 1)) : createCommentVNode("", true),
                      createVNode("form", {
                        onSubmit: withModifiers(submit, ["prevent"])
                      }, [
                        createVNode("div", { class: "flex flex-col my-5 gap-2" }, [
                          createVNode("label", {
                            for: "card-number",
                            class: "text-sm font-medium text-gray-700"
                          }, [
                            createTextVNode(" カード番号： "),
                            createVNode("div", {
                              id: "card-number",
                              class: "border p-2 w-full"
                            })
                          ]),
                          createVNode("label", {
                            for: "card-expiry",
                            class: "text-sm font-medium text-gray-700"
                          }, [
                            createTextVNode(" 有効期限： "),
                            createVNode("div", {
                              id: "card-expiry",
                              class: "border p-2 w-full"
                            })
                          ]),
                          createVNode("label", {
                            for: "card-cvc",
                            class: "text-sm font-medium text-gray-700"
                          }, [
                            createTextVNode(" セキュリティコード： "),
                            createVNode("div", {
                              id: "card-cvc",
                              class: "border p-2 w-full"
                            })
                          ])
                        ]),
                        createVNode("button", {
                          type: "submit",
                          class: "btn"
                        }, "決済する")
                      ], 32)
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  createVNode("div", { class: "w-full border p-2" }, [
                    createVNode("div", { class: "flex flex-col gap-2" }, [
                      createVNode("span", null, "合計金額（税抜き）：" + toDisplayString(unref(props).totalPrice.total_price_excluding_tax) + " 円", 1),
                      createVNode("span", null, "決済金額（税込み）：" + toDisplayString(unref(props).totalPrice.total_price_including_tax) + " 円", 1)
                    ]),
                    error_message.value ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "text-red-600"
                    }, toDisplayString(error_message.value), 1)) : createCommentVNode("", true),
                    createVNode("form", {
                      onSubmit: withModifiers(submit, ["prevent"])
                    }, [
                      createVNode("div", { class: "flex flex-col my-5 gap-2" }, [
                        createVNode("label", {
                          for: "card-number",
                          class: "text-sm font-medium text-gray-700"
                        }, [
                          createTextVNode(" カード番号： "),
                          createVNode("div", {
                            id: "card-number",
                            class: "border p-2 w-full"
                          })
                        ]),
                        createVNode("label", {
                          for: "card-expiry",
                          class: "text-sm font-medium text-gray-700"
                        }, [
                          createTextVNode(" 有効期限： "),
                          createVNode("div", {
                            id: "card-expiry",
                            class: "border p-2 w-full"
                          })
                        ]),
                        createVNode("label", {
                          for: "card-cvc",
                          class: "text-sm font-medium text-gray-700"
                        }, [
                          createTextVNode(" セキュリティコード： "),
                          createVNode("div", {
                            id: "card-cvc",
                            class: "border p-2 w-full"
                          })
                        ])
                      ]),
                      createVNode("button", {
                        type: "submit",
                        class: "btn"
                      }, "決済する")
                    ], 32)
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/PaymentComponent.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
