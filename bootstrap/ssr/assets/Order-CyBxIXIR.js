import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, Fragment, renderList, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Order",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Order" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Order</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Order")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (unref(props).data) {
                    _push3(`<div class="flex flex-col gap-5"${_scopeId2}><div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>オーダー</p><p${_scopeId2}>オーダー状況</p></div><div class="overflow-x-auto"${_scopeId2}><table class="table"${_scopeId2}><thead${_scopeId2}><tr${_scopeId2}><th${_scopeId2}></th><th${_scopeId2}>日付</th><th${_scopeId2}>金額</th><th${_scopeId2}>決済状況</th><th${_scopeId2}></th></tr></thead><tbody${_scopeId2}><!--[-->`);
                    ssrRenderList(unref(props).data, (order) => {
                      _push3(`<tr${_scopeId2}><th${_scopeId2}>${ssrInterpolate(order.id)}</th><td${_scopeId2}>${ssrInterpolate(order.created_at)}</td><td${_scopeId2}>${ssrInterpolate(order.total_amount)}</td><td${_scopeId2}>`);
                      if (order.status === "succeeded") {
                        _push3(`<!--[--> 済み <!--]-->`);
                      } else {
                        _push3(`<!--[--> 保留中 <!--]-->`);
                      }
                      _push3(`</td><td${_scopeId2}>`);
                      _push3(ssrRenderComponent(unref(Link), {
                        href: _ctx.route("order.show", { id: order.id }),
                        class: "btn btn-sm"
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            _push4(`詳細`);
                          } else {
                            return [
                              createTextVNode("詳細")
                            ];
                          }
                        }),
                        _: 2
                      }, _parent3, _scopeId2));
                      _push3(`</td></tr>`);
                    });
                    _push3(`<!--]--></tbody></table></div></div>`);
                  } else {
                    _push3(`<div${_scopeId2}> 注文はありません </div>`);
                  }
                } else {
                  return [
                    unref(props).data ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex flex-col gap-5"
                    }, [
                      createVNode("div", { class: "text-center mb-5" }, [
                        createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "オーダー"),
                        createVNode("p", null, "オーダー状況")
                      ]),
                      createVNode("div", { class: "overflow-x-auto" }, [
                        createVNode("table", { class: "table" }, [
                          createVNode("thead", null, [
                            createVNode("tr", null, [
                              createVNode("th"),
                              createVNode("th", null, "日付"),
                              createVNode("th", null, "金額"),
                              createVNode("th", null, "決済状況"),
                              createVNode("th")
                            ])
                          ]),
                          createVNode("tbody", null, [
                            (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (order) => {
                              return openBlock(), createBlock("tr", {
                                key: order.id
                              }, [
                                createVNode("th", null, toDisplayString(order.id), 1),
                                createVNode("td", null, toDisplayString(order.created_at), 1),
                                createVNode("td", null, toDisplayString(order.total_amount), 1),
                                createVNode("td", null, [
                                  order.status === "succeeded" ? (openBlock(), createBlock(Fragment, { key: 0 }, [
                                    createTextVNode(" 済み ")
                                  ], 64)) : (openBlock(), createBlock(Fragment, { key: 1 }, [
                                    createTextVNode(" 保留中 ")
                                  ], 64))
                                ]),
                                createVNode("td", null, [
                                  createVNode(unref(Link), {
                                    href: _ctx.route("order.show", { id: order.id }),
                                    class: "btn btn-sm"
                                  }, {
                                    default: withCtx(() => [
                                      createTextVNode("詳細")
                                    ]),
                                    _: 2
                                  }, 1032, ["href"])
                                ])
                              ]);
                            }), 128))
                          ])
                        ])
                      ])
                    ])) : (openBlock(), createBlock("div", { key: 1 }, " 注文はありません "))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  unref(props).data ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "flex flex-col gap-5"
                  }, [
                    createVNode("div", { class: "text-center mb-5" }, [
                      createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "オーダー"),
                      createVNode("p", null, "オーダー状況")
                    ]),
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "table" }, [
                        createVNode("thead", null, [
                          createVNode("tr", null, [
                            createVNode("th"),
                            createVNode("th", null, "日付"),
                            createVNode("th", null, "金額"),
                            createVNode("th", null, "決済状況"),
                            createVNode("th")
                          ])
                        ]),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (order) => {
                            return openBlock(), createBlock("tr", {
                              key: order.id
                            }, [
                              createVNode("th", null, toDisplayString(order.id), 1),
                              createVNode("td", null, toDisplayString(order.created_at), 1),
                              createVNode("td", null, toDisplayString(order.total_amount), 1),
                              createVNode("td", null, [
                                order.status === "succeeded" ? (openBlock(), createBlock(Fragment, { key: 0 }, [
                                  createTextVNode(" 済み ")
                                ], 64)) : (openBlock(), createBlock(Fragment, { key: 1 }, [
                                  createTextVNode(" 保留中 ")
                                ], 64))
                              ]),
                              createVNode("td", null, [
                                createVNode(unref(Link), {
                                  href: _ctx.route("order.show", { id: order.id }),
                                  class: "btn btn-sm"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode("詳細")
                                  ]),
                                  _: 2
                                }, 1032, ["href"])
                              ])
                            ]);
                          }), 128))
                        ])
                      ])
                    ])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, " 注文はありません "))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Order.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
