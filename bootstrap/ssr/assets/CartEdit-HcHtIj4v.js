import { defineComponent, unref, withCtx, createVNode, withDirectives, toDisplayString, vShow, openBlock, createBlock, withModifiers, vModelText, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { _ as _sfc_main$3 } from "./EcImageGallery-BXIFEkZy.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartEdit",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({
      quantity: props.data.quantity
    });
    const submit = () => {
      form.put(route("cart.update", { id: props.data.id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    const deleteCartItem = (id) => {
      form.delete(route("cart.destroy", { id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CartEdit" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Cart Edit</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Cart Edit")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId2}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId2}>${ssrInterpolate(unref(props).flash.success)}</p></div>`);
                  if (unref(props).data && unref(props).data.product) {
                    _push3(`<div class="flex gap-5"${_scopeId2}><div${_scopeId2}>`);
                    _push3(ssrRenderComponent(_sfc_main$3, {
                      images: unref(props).data.product.image
                    }, null, _parent3, _scopeId2));
                    _push3(`</div><div${_scopeId2}><form enctype="multipart/form-data"${_scopeId2}><ul${_scopeId2}><li${_scopeId2}>${ssrInterpolate(unref(props).data.id)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.product.name)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.product.description)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.product.price_excluding_tax)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.product.price_including_tax)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.product.tax_rate)}</li><li${_scopeId2}><input type="number"${ssrRenderAttr("value", unref(form).quantity)}${_scopeId2}></li><li${_scopeId2}>${ssrInterpolate(unref(props).data.created_at)}</li><li class="flex gap-2"${_scopeId2}><button type="submit" class="btn"${_scopeId2}>数量変更</button><button class="btn"${_scopeId2}>カートから削除</button></li></ul></form></div></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                    ], 512), [
                      [vShow, unref(props).flash.success]
                    ]),
                    unref(props).data && unref(props).data.product ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex gap-5"
                    }, [
                      createVNode("div", null, [
                        createVNode(_sfc_main$3, {
                          images: unref(props).data.product.image
                        }, null, 8, ["images"])
                      ]),
                      createVNode("div", null, [
                        createVNode("form", {
                          onSubmit: withModifiers(submit, ["prevent"]),
                          enctype: "multipart/form-data"
                        }, [
                          createVNode("ul", null, [
                            createVNode("li", null, toDisplayString(unref(props).data.id), 1),
                            createVNode("li", null, toDisplayString(unref(props).data.product.name), 1),
                            createVNode("li", null, toDisplayString(unref(props).data.product.description), 1),
                            createVNode("li", null, toDisplayString(unref(props).data.product.price_excluding_tax), 1),
                            createVNode("li", null, toDisplayString(unref(props).data.product.price_including_tax), 1),
                            createVNode("li", null, toDisplayString(unref(props).data.product.tax_rate), 1),
                            createVNode("li", null, [
                              withDirectives(createVNode("input", {
                                type: "number",
                                "onUpdate:modelValue": ($event) => unref(form).quantity = $event
                              }, null, 8, ["onUpdate:modelValue"]), [
                                [vModelText, unref(form).quantity]
                              ])
                            ]),
                            createVNode("li", null, toDisplayString(unref(props).data.created_at), 1),
                            createVNode("li", { class: "flex gap-2" }, [
                              createVNode("button", {
                                type: "submit",
                                class: "btn"
                              }, "数量変更"),
                              createVNode("button", {
                                onClick: ($event) => deleteCartItem(unref(props).data.id),
                                class: "btn"
                              }, "カートから削除", 8, ["onClick"])
                            ])
                          ])
                        ], 32)
                      ])
                    ])) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  withDirectives(createVNode("div", null, [
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                  ], 512), [
                    [vShow, unref(props).flash.success]
                  ]),
                  unref(props).data && unref(props).data.product ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "flex gap-5"
                  }, [
                    createVNode("div", null, [
                      createVNode(_sfc_main$3, {
                        images: unref(props).data.product.image
                      }, null, 8, ["images"])
                    ]),
                    createVNode("div", null, [
                      createVNode("form", {
                        onSubmit: withModifiers(submit, ["prevent"]),
                        enctype: "multipart/form-data"
                      }, [
                        createVNode("ul", null, [
                          createVNode("li", null, toDisplayString(unref(props).data.id), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.product.name), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.product.description), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.product.price_excluding_tax), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.product.price_including_tax), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.product.tax_rate), 1),
                          createVNode("li", null, [
                            withDirectives(createVNode("input", {
                              type: "number",
                              "onUpdate:modelValue": ($event) => unref(form).quantity = $event
                            }, null, 8, ["onUpdate:modelValue"]), [
                              [vModelText, unref(form).quantity]
                            ])
                          ]),
                          createVNode("li", null, toDisplayString(unref(props).data.created_at), 1),
                          createVNode("li", { class: "flex gap-2" }, [
                            createVNode("button", {
                              type: "submit",
                              class: "btn"
                            }, "数量変更"),
                            createVNode("button", {
                              onClick: ($event) => deleteCartItem(unref(props).data.id),
                              class: "btn"
                            }, "カートから削除", 8, ["onClick"])
                          ])
                        ])
                      ], 32)
                    ])
                  ])) : createCommentVNode("", true)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/CartEdit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
