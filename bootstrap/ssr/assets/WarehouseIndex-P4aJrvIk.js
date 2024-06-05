import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, withDirectives, toDisplayString, vShow, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "WarehouseIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({});
    const deleteWarehouse = (id) => {
      form.delete(route("admin.warehouse.destroy", { id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Warehouse Index" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Warehouse Index</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Warehouse Index")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (unref(props).data.length) {
              _push2(`<div${_scopeId}><div${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.warehouse.create"),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`倉庫を登録`);
                  } else {
                    return [
                      createTextVNode("倉庫を登録")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div><div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div><!--[-->`);
              ssrRenderList(unref(props).data, (warehouse) => {
                _push2(`<ul${_scopeId}><li${_scopeId}>${ssrInterpolate(warehouse.id)}</li><li${_scopeId}>${ssrInterpolate(warehouse.name)}</li><li${_scopeId}>${ssrInterpolate(warehouse.location)}</li><li class="flex gap-2"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Link), {
                  class: "btn btn-sm",
                  href: _ctx.route("admin.warehouse.show", { id: warehouse.id })
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`一覧`);
                    } else {
                      return [
                        createTextVNode("一覧")
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
                _push2(ssrRenderComponent(unref(Link), {
                  class: "btn btn-sm",
                  href: _ctx.route("admin.warehouse.edit", { id: warehouse.id })
                }, {
                  default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                    if (_push3) {
                      _push3(`編集`);
                    } else {
                      return [
                        createTextVNode("編集")
                      ];
                    }
                  }),
                  _: 2
                }, _parent2, _scopeId));
                _push2(`<button class="btn btn-sm"${_scopeId}>削除</button></li></ul>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<div${_scopeId}><p${_scopeId}>倉庫が登録されていません</p></div>`);
            }
          } else {
            return [
              unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("div", null, [
                  createVNode(unref(Link), {
                    href: _ctx.route("admin.warehouse.create"),
                    class: "btn"
                  }, {
                    default: withCtx(() => [
                      createTextVNode("倉庫を登録")
                    ]),
                    _: 1
                  }, 8, ["href"])
                ]),
                withDirectives(createVNode("div", null, [
                  createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                ], 512), [
                  [vShow, unref(props).flash.success]
                ]),
                (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (warehouse) => {
                  return openBlock(), createBlock("ul", {
                    key: warehouse.id
                  }, [
                    createVNode("li", null, toDisplayString(warehouse.id), 1),
                    createVNode("li", null, toDisplayString(warehouse.name), 1),
                    createVNode("li", null, toDisplayString(warehouse.location), 1),
                    createVNode("li", { class: "flex gap-2" }, [
                      createVNode(unref(Link), {
                        class: "btn btn-sm",
                        href: _ctx.route("admin.warehouse.show", { id: warehouse.id })
                      }, {
                        default: withCtx(() => [
                          createTextVNode("一覧")
                        ]),
                        _: 2
                      }, 1032, ["href"]),
                      createVNode(unref(Link), {
                        class: "btn btn-sm",
                        href: _ctx.route("admin.warehouse.edit", { id: warehouse.id })
                      }, {
                        default: withCtx(() => [
                          createTextVNode("編集")
                        ]),
                        _: 2
                      }, 1032, ["href"]),
                      createVNode("button", {
                        onClick: ($event) => deleteWarehouse(warehouse.id),
                        class: "btn btn-sm"
                      }, "削除", 8, ["onClick"])
                    ])
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("div", { key: 1 }, [
                createVNode("p", null, "倉庫が登録されていません")
              ]))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/WarehouseIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
