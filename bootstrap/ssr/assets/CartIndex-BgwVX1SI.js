import { defineComponent, unref, withCtx, createVNode, createTextVNode, withDirectives, toDisplayString, vShow, openBlock, createBlock, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate } from "vue/server-renderer";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { _ as _sfc_main$3 } from "./CartTableBody-DmWkNu-v.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CartIndex" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Cart</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Cart")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>カート</p><p${_scopeId2}>カート内容です</p></div><div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId2}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId2}>${ssrInterpolate(unref(props).flash.success)}</p></div>`);
                  if (unref(props).data) {
                    _push3(`<div class="overflow-x-auto"${_scopeId2}><table class="table"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th${_scopeId2}>商品名</th><th${_scopeId2}>数量</th><th${_scopeId2}>料金</th><th${_scopeId2}></th></tr></thead>`);
                    _push3(ssrRenderComponent(_sfc_main$3, {
                      carts: unref(props).data
                    }, null, _parent3, _scopeId2));
                    _push3(`<tfoot${_scopeId2}><tr${_scopeId2}><th colspan="2"${_scopeId2}>合計</th><th colspan="2" class="text-right"${_scopeId2}><span class="text-lg"${_scopeId2}>${ssrInterpolate(unref(props).totalPrice.total_price_excluding_tax)}円(税抜き) </span><br${_scopeId2}><span class="text-lg"${_scopeId2}>${ssrInterpolate(unref(props).totalPrice.total_price_including_tax)}円(税込み) </span></th></tr></tfoot></table><div${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Link), {
                      href: _ctx.route("payment.index"),
                      class: "btn"
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`購入画面`);
                        } else {
                          return [
                            createTextVNode("購入画面")
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                    _push3(`</div></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    createVNode("div", { class: "text-center mb-5" }, [
                      createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "カート"),
                      createVNode("p", null, "カート内容です")
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                    ], 512), [
                      [vShow, unref(props).flash.success]
                    ]),
                    unref(props).data ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "overflow-x-auto"
                    }, [
                      createVNode("table", { class: "table" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", null, "商品名"),
                            createVNode("th", null, "数量"),
                            createVNode("th", null, "料金"),
                            createVNode("th")
                          ])
                        ]),
                        createVNode(_sfc_main$3, {
                          carts: unref(props).data
                        }, null, 8, ["carts"]),
                        createVNode("tfoot", null, [
                          createVNode("tr", null, [
                            createVNode("th", { colspan: "2" }, "合計"),
                            createVNode("th", {
                              colspan: "2",
                              class: "text-right"
                            }, [
                              createVNode("span", { class: "text-lg" }, toDisplayString(unref(props).totalPrice.total_price_excluding_tax) + "円(税抜き) ", 1),
                              createVNode("br"),
                              createVNode("span", { class: "text-lg" }, toDisplayString(unref(props).totalPrice.total_price_including_tax) + "円(税込み) ", 1)
                            ])
                          ])
                        ])
                      ]),
                      createVNode("div", null, [
                        createVNode(unref(Link), {
                          href: _ctx.route("payment.index"),
                          class: "btn"
                        }, {
                          default: withCtx(() => [
                            createTextVNode("購入画面")
                          ]),
                          _: 1
                        }, 8, ["href"])
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
                  createVNode("div", { class: "text-center mb-5" }, [
                    createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "カート"),
                    createVNode("p", null, "カート内容です")
                  ]),
                  withDirectives(createVNode("div", null, [
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                  ], 512), [
                    [vShow, unref(props).flash.success]
                  ]),
                  unref(props).data ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "overflow-x-auto"
                  }, [
                    createVNode("table", { class: "table" }, [
                      createVNode("thead", null, [
                        createVNode("tr", null, [
                          createVNode("th", null, "商品名"),
                          createVNode("th", null, "数量"),
                          createVNode("th", null, "料金"),
                          createVNode("th")
                        ])
                      ]),
                      createVNode(_sfc_main$3, {
                        carts: unref(props).data
                      }, null, 8, ["carts"]),
                      createVNode("tfoot", null, [
                        createVNode("tr", null, [
                          createVNode("th", { colspan: "2" }, "合計"),
                          createVNode("th", {
                            colspan: "2",
                            class: "text-right"
                          }, [
                            createVNode("span", { class: "text-lg" }, toDisplayString(unref(props).totalPrice.total_price_excluding_tax) + "円(税抜き) ", 1),
                            createVNode("br"),
                            createVNode("span", { class: "text-lg" }, toDisplayString(unref(props).totalPrice.total_price_including_tax) + "円(税込み) ", 1)
                          ])
                        ])
                      ])
                    ]),
                    createVNode("div", null, [
                      createVNode(unref(Link), {
                        href: _ctx.route("payment.index"),
                        class: "btn"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("購入画面")
                        ]),
                        _: 1
                      }, 8, ["href"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/CartIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
