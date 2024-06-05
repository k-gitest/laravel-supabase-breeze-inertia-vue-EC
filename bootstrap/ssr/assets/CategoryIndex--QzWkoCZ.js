import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, withDirectives, toDisplayString, vShow, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CategoryIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({});
    const deleteCategory = (id) => {
      form.delete(route("admin.category.destroy", { id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CategoryIndex" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>CategoryIndex</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "CategoryIndex")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("admin.category.create"),
              class: "btn btn-sm"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`新規作成`);
                } else {
                  return [
                    createTextVNode("新規作成")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div>`);
            if (unref(props).data.length) {
              _push2(`<div${_scopeId}><div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div><!--[-->`);
              ssrRenderList(unref(props).data, (category) => {
                _push2(`<ul${_scopeId}><li${_scopeId}>${ssrInterpolate(category.id)}</li><li${_scopeId}>${ssrInterpolate(category.name)}</li><li${_scopeId}>${ssrInterpolate(category.description)}</li><li${_scopeId}>${ssrInterpolate(category.created_at)}</li><li${_scopeId}>${ssrInterpolate(category.updated_at)}</li><li class="flex gap-2"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Link), {
                  class: "btn",
                  href: _ctx.route("admin.category.edit", { id: category.id })
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
                _push2(`<button class="btn"${_scopeId}>削除</button></li></ul>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<div${_scopeId}><p${_scopeId}>カテゴリーが登録されていません</p></div>`);
            }
          } else {
            return [
              createVNode("div", null, [
                createVNode(unref(Link), {
                  href: _ctx.route("admin.category.create"),
                  class: "btn btn-sm"
                }, {
                  default: withCtx(() => [
                    createTextVNode("新規作成")
                  ]),
                  _: 1
                }, 8, ["href"])
              ]),
              unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                withDirectives(createVNode("div", null, [
                  createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                ], 512), [
                  [vShow, unref(props).flash.success]
                ]),
                (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (category) => {
                  return openBlock(), createBlock("ul", {
                    key: category.id
                  }, [
                    createVNode("li", null, toDisplayString(category.id), 1),
                    createVNode("li", null, toDisplayString(category.name), 1),
                    createVNode("li", null, toDisplayString(category.description), 1),
                    createVNode("li", null, toDisplayString(category.created_at), 1),
                    createVNode("li", null, toDisplayString(category.updated_at), 1),
                    createVNode("li", { class: "flex gap-2" }, [
                      createVNode(unref(Link), {
                        class: "btn",
                        href: _ctx.route("admin.category.edit", { id: category.id })
                      }, {
                        default: withCtx(() => [
                          createTextVNode("編集")
                        ]),
                        _: 2
                      }, 1032, ["href"]),
                      createVNode("button", {
                        onClick: ($event) => deleteCategory(category.id),
                        class: "btn"
                      }, "削除", 8, ["onClick"])
                    ])
                  ]);
                }), 128))
              ])) : (openBlock(), createBlock("div", { key: 1 }, [
                createVNode("p", null, "カテゴリーが登録されていません")
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/CategoryIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
