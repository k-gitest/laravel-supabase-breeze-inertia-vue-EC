import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, Fragment, renderList, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  setup(__props) {
    const { component, props: { data: todoList }, url, version } = usePage();
    const form = useForm({});
    const destroy = async (id) => {
      await form.delete(route("todo.destroy", { id }), {
        preserveScroll: true,
        preserveState: false
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "TodoList" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Todo</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Todo")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<main class="flex flex-col items-center justify-center w-full"${_scopeId}><h1 class="text-4xl font-bold p-2"${_scopeId}>todoリストのページ</h1>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: _ctx.route("todo.register"),
              class: "btn"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(` 新規追加 `);
                } else {
                  return [
                    createTextVNode(" 新規追加 ")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            if (unref(todoList).length) {
              _push2(`<div${_scopeId}><!--[-->`);
              ssrRenderList(unref(todoList), (todo) => {
                _push2(`<ul${_scopeId}><li${_scopeId}>${ssrInterpolate(todo.id)}</li><li${_scopeId}>${ssrInterpolate(todo.name)}</li><li${_scopeId}>${ssrInterpolate(todo.created_at)}</li><li${_scopeId}>${ssrInterpolate(todo.updated_at)}</li><li${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Link), {
                  class: "btn",
                  href: _ctx.route("todo.edit", { id: todo.id })
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
                _push2(`</li><li${_scopeId}><button class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>削除</button></li></ul>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<div${_scopeId}><p${_scopeId}>Todoデータがありません</p></div>`);
            }
            _push2(`</main>`);
          } else {
            return [
              createVNode("main", { class: "flex flex-col items-center justify-center w-full" }, [
                createVNode("h1", { class: "text-4xl font-bold p-2" }, "todoリストのページ"),
                createVNode(unref(Link), {
                  href: _ctx.route("todo.register"),
                  class: "btn"
                }, {
                  default: withCtx(() => [
                    createTextVNode(" 新規追加 ")
                  ]),
                  _: 1
                }, 8, ["href"]),
                unref(todoList).length ? (openBlock(), createBlock("div", { key: 0 }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(unref(todoList), (todo) => {
                    return openBlock(), createBlock("ul", {
                      key: todo.id
                    }, [
                      createVNode("li", null, toDisplayString(todo.id), 1),
                      createVNode("li", null, toDisplayString(todo.name), 1),
                      createVNode("li", null, toDisplayString(todo.created_at), 1),
                      createVNode("li", null, toDisplayString(todo.updated_at), 1),
                      createVNode("li", null, [
                        createVNode(unref(Link), {
                          class: "btn",
                          href: _ctx.route("todo.edit", { id: todo.id })
                        }, {
                          default: withCtx(() => [
                            createTextVNode("編集")
                          ]),
                          _: 2
                        }, 1032, ["href"])
                      ]),
                      createVNode("li", null, [
                        createVNode("button", {
                          class: "btn",
                          onClick: ($event) => destroy(todo.id),
                          disabled: unref(form).processing
                        }, "削除", 8, ["onClick", "disabled"])
                      ])
                    ]);
                  }), 128))
                ])) : (openBlock(), createBlock("div", { key: 1 }, [
                  createVNode("p", null, "Todoデータがありません")
                ]))
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Todo/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
