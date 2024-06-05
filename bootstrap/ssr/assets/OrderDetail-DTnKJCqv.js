import { defineComponent, ref, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, toDisplayString, Fragment, renderList, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "OrderDetail",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const totalPrice = ref(0);
    props.data.map((product) => {
      totalPrice.value += product.price_inclusing_tax * product.quantity;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Order Detail" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Order Detail</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Order Detail")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (unref(props).data && unref(props).data.length) {
                    _push3(`<div class="flex flex-col gap-5"${_scopeId2}><div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>オーダー</p><p${_scopeId2}>${ssrInterpolate(unref(props).data[0].created_at)}のオーダー内容</p></div><div class="overflow-x-auto"${_scopeId2}><table class="table"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th${_scopeId2}>商品名</th><th${_scopeId2}>料金</th><th${_scopeId2}>数量</th><th${_scopeId2}>小計</th><th${_scopeId2}></th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(unref(props).data, (order_item) => {
                      _push3(`<!--[-->`);
                      if (order_item.product) {
                        _push3(`<tr${_scopeId2}><td${_scopeId2}><div class="flex items-center gap-3"${_scopeId2}><div class="avatar"${_scopeId2}><div class="mask mask-squircle w-12 h-12"${_scopeId2}>`);
                        if (order_item.product.image && order_item.product.image.length) {
                          _push3(`<img${ssrRenderAttr("src", unref(supabaseURL) + order_item.product.image[0].path)}${_scopeId2}>`);
                        } else {
                          _push3(`<img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))}${_scopeId2}>`);
                        }
                        _push3(`</div></div><div${_scopeId2}><div class="font-bold"${_scopeId2}>${ssrInterpolate(order_item.product.name)}</div><div class="text-sm opacity-50"${_scopeId2}>${ssrInterpolate(order_item.product.description)}</div></div></div></td><td${_scopeId2}>${ssrInterpolate(Math.round(order_item.product.price_excluding_tax))}円(税抜き)<br${_scopeId2}> ${ssrInterpolate(Math.round(order_item.product.price_including_tax))}円(税込み) </td><td${_scopeId2}>${ssrInterpolate(order_item.quantity)}</td><td${_scopeId2}>${ssrInterpolate(Math.round(order_item.product.price_including_tax) * order_item.quantity)}円(税込み)</td><th${_scopeId2}>`);
                        _push3(ssrRenderComponent(unref(Link), {
                          href: _ctx.route("product.show", { id: order_item.product.id }),
                          class: "btn btn-ghost btn-xs"
                        }, {
                          default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                            if (_push4) {
                              _push4(`details`);
                            } else {
                              return [
                                createTextVNode("details")
                              ];
                            }
                          }),
                          _: 2
                        }, _parent3, _scopeId2));
                        _push3(`</th></tr>`);
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`<!--]-->`);
                    });
                    _push3(`<!--]--></tbody><tfoot${_scopeId2}><tr${_scopeId2}><th colspan="3"${_scopeId2}>合計</th><th${_scopeId2}>${ssrInterpolate(Math.round(totalPrice.value))}円(税込み)</th><th${_scopeId2}></th></tr></tfoot></table></div></div>`);
                  } else {
                    _push3(`<div${_scopeId2}> 商品がありません </div>`);
                  }
                } else {
                  return [
                    unref(props).data && unref(props).data.length ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex flex-col gap-5"
                    }, [
                      createVNode("div", { class: "text-center mb-5" }, [
                        createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "オーダー"),
                        createVNode("p", null, toDisplayString(unref(props).data[0].created_at) + "のオーダー内容", 1)
                      ]),
                      createVNode("div", { class: "overflow-x-auto" }, [
                        createVNode("table", { class: "table" }, [
                          createVNode("thead", null, [
                            createVNode("tr", null, [
                              createVNode("th", null, "商品名"),
                              createVNode("th", null, "料金"),
                              createVNode("th", null, "数量"),
                              createVNode("th", null, "小計"),
                              createVNode("th")
                            ])
                          ]),
                          createVNode("tbody", null, [
                            (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (order_item) => {
                              return openBlock(), createBlock(Fragment, {
                                key: order_item.id
                              }, [
                                order_item.product ? (openBlock(), createBlock("tr", { key: 0 }, [
                                  createVNode("td", null, [
                                    createVNode("div", { class: "flex items-center gap-3" }, [
                                      createVNode("div", { class: "avatar" }, [
                                        createVNode("div", { class: "mask mask-squircle w-12 h-12" }, [
                                          order_item.product.image && order_item.product.image.length ? (openBlock(), createBlock("img", {
                                            key: 0,
                                            src: unref(supabaseURL) + order_item.product.image[0].path
                                          }, null, 8, ["src"])) : (openBlock(), createBlock("img", {
                                            key: 1,
                                            src: unref(supabaseURL) + unref(supabaseNoImage)
                                          }, null, 8, ["src"]))
                                        ])
                                      ]),
                                      createVNode("div", null, [
                                        createVNode("div", { class: "font-bold" }, toDisplayString(order_item.product.name), 1),
                                        createVNode("div", { class: "text-sm opacity-50" }, toDisplayString(order_item.product.description), 1)
                                      ])
                                    ])
                                  ]),
                                  createVNode("td", null, [
                                    createTextVNode(toDisplayString(Math.round(order_item.product.price_excluding_tax)) + "円(税抜き)", 1),
                                    createVNode("br"),
                                    createTextVNode(" " + toDisplayString(Math.round(order_item.product.price_including_tax)) + "円(税込み) ", 1)
                                  ]),
                                  createVNode("td", null, toDisplayString(order_item.quantity), 1),
                                  createVNode("td", null, toDisplayString(Math.round(order_item.product.price_including_tax) * order_item.quantity) + "円(税込み)", 1),
                                  createVNode("th", null, [
                                    createVNode(unref(Link), {
                                      href: _ctx.route("product.show", { id: order_item.product.id }),
                                      class: "btn btn-ghost btn-xs"
                                    }, {
                                      default: withCtx(() => [
                                        createTextVNode("details")
                                      ]),
                                      _: 2
                                    }, 1032, ["href"])
                                  ])
                                ])) : createCommentVNode("", true)
                              ], 64);
                            }), 128))
                          ]),
                          createVNode("tfoot", null, [
                            createVNode("tr", null, [
                              createVNode("th", { colspan: "3" }, "合計"),
                              createVNode("th", null, toDisplayString(Math.round(totalPrice.value)) + "円(税込み)", 1),
                              createVNode("th")
                            ])
                          ])
                        ])
                      ])
                    ])) : (openBlock(), createBlock("div", { key: 1 }, " 商品がありません "))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  unref(props).data && unref(props).data.length ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "flex flex-col gap-5"
                  }, [
                    createVNode("div", { class: "text-center mb-5" }, [
                      createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "オーダー"),
                      createVNode("p", null, toDisplayString(unref(props).data[0].created_at) + "のオーダー内容", 1)
                    ]),
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "table" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th", null, "商品名"),
                            createVNode("th", null, "料金"),
                            createVNode("th", null, "数量"),
                            createVNode("th", null, "小計"),
                            createVNode("th")
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (order_item) => {
                            return openBlock(), createBlock(Fragment, {
                              key: order_item.id
                            }, [
                              order_item.product ? (openBlock(), createBlock("tr", { key: 0 }, [
                                createVNode("td", null, [
                                  createVNode("div", { class: "flex items-center gap-3" }, [
                                    createVNode("div", { class: "avatar" }, [
                                      createVNode("div", { class: "mask mask-squircle w-12 h-12" }, [
                                        order_item.product.image && order_item.product.image.length ? (openBlock(), createBlock("img", {
                                          key: 0,
                                          src: unref(supabaseURL) + order_item.product.image[0].path
                                        }, null, 8, ["src"])) : (openBlock(), createBlock("img", {
                                          key: 1,
                                          src: unref(supabaseURL) + unref(supabaseNoImage)
                                        }, null, 8, ["src"]))
                                      ])
                                    ]),
                                    createVNode("div", null, [
                                      createVNode("div", { class: "font-bold" }, toDisplayString(order_item.product.name), 1),
                                      createVNode("div", { class: "text-sm opacity-50" }, toDisplayString(order_item.product.description), 1)
                                    ])
                                  ])
                                ]),
                                createVNode("td", null, [
                                  createTextVNode(toDisplayString(Math.round(order_item.product.price_excluding_tax)) + "円(税抜き)", 1),
                                  createVNode("br"),
                                  createTextVNode(" " + toDisplayString(Math.round(order_item.product.price_including_tax)) + "円(税込み) ", 1)
                                ]),
                                createVNode("td", null, toDisplayString(order_item.quantity), 1),
                                createVNode("td", null, toDisplayString(Math.round(order_item.product.price_including_tax) * order_item.quantity) + "円(税込み)", 1),
                                createVNode("th", null, [
                                  createVNode(unref(Link), {
                                    href: _ctx.route("product.show", { id: order_item.product.id }),
                                    class: "btn btn-ghost btn-xs"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode("details")
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ])
                              ])) : createCommentVNode("", true)
                            ], 64);
                          }), 128))
                        ]),
                        createVNode("tfoot", null, [
                          createVNode("tr", null, [
                            createVNode("th", { colspan: "3" }, "合計"),
                            createVNode("th", null, toDisplayString(Math.round(totalPrice.value)) + "円(税込み)", 1),
                            createVNode("th")
                          ])
                        ])
                      ])
                    ])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, " 商品がありません "))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/OrderDetail.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
