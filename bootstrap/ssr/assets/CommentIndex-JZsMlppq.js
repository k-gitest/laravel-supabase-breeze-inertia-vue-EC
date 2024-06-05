import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, Fragment, renderList, toDisplayString, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CommentIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const deleteComment = (id) => {
      router.delete(`/comment/destroy/${id}`, {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CommentIndex" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Comment Index</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Comment Index")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (unref(props).data.length) {
                    _push3(`<div${_scopeId2}><div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>コメント投稿一覧</p></div><div class="grid grid-cols-4 gap-5"${_scopeId2}><!--[-->`);
                    ssrRenderList(unref(props).data, (comment) => {
                      _push3(`<div class="card w-96 bg-base-100 border"${_scopeId2}><div class="card-body"${_scopeId2}>`);
                      if (comment.product) {
                        _push3(`<div${_scopeId2}>${ssrInterpolate(comment.product.name)}へのコメント投稿 </div>`);
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`<h2 class="card-title"${_scopeId2}>${ssrInterpolate(comment.title)}</h2><p${_scopeId2}>${ssrInterpolate(comment.content)}</p><div class="card-actions justify-end"${_scopeId2}>${ssrInterpolate(comment.created_at)} `);
                      if (comment.product) {
                        _push3(ssrRenderComponent(unref(Link), {
                          href: _ctx.route("product.show", { id: comment.product.id }),
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
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`<button class="btn btn-sm"${_scopeId2}>削除</button></div></div></div>`);
                    });
                    _push3(`<!--]--></div></div>`);
                  } else {
                    _push3(`<div${_scopeId2}> コメント投稿はありません </div>`);
                  }
                } else {
                  return [
                    unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                      createVNode("div", { class: "text-center mb-5" }, [
                        createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "コメント投稿一覧")
                      ]),
                      createVNode("div", { class: "grid grid-cols-4 gap-5" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (comment) => {
                          return openBlock(), createBlock("div", {
                            key: comment.id,
                            class: "card w-96 bg-base-100 border"
                          }, [
                            createVNode("div", { class: "card-body" }, [
                              comment.product ? (openBlock(), createBlock("div", { key: 0 }, toDisplayString(comment.product.name) + "へのコメント投稿 ", 1)) : createCommentVNode("", true),
                              createVNode("h2", { class: "card-title" }, toDisplayString(comment.title), 1),
                              createVNode("p", null, toDisplayString(comment.content), 1),
                              createVNode("div", { class: "card-actions justify-end" }, [
                                createTextVNode(toDisplayString(comment.created_at) + " ", 1),
                                comment.product ? (openBlock(), createBlock(unref(Link), {
                                  key: 0,
                                  href: _ctx.route("product.show", { id: comment.product.id }),
                                  class: "btn btn-sm"
                                }, {
                                  default: withCtx(() => [
                                    createTextVNode("詳細")
                                  ]),
                                  _: 2
                                }, 1032, ["href"])) : createCommentVNode("", true),
                                createVNode("button", {
                                  onClick: ($event) => deleteComment(comment.id),
                                  class: "btn btn-sm"
                                }, "削除", 8, ["onClick"])
                              ])
                            ])
                          ]);
                        }), 128))
                      ])
                    ])) : (openBlock(), createBlock("div", { key: 1 }, " コメント投稿はありません "))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                    createVNode("div", { class: "text-center mb-5" }, [
                      createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "コメント投稿一覧")
                    ]),
                    createVNode("div", { class: "grid grid-cols-4 gap-5" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (comment) => {
                        return openBlock(), createBlock("div", {
                          key: comment.id,
                          class: "card w-96 bg-base-100 border"
                        }, [
                          createVNode("div", { class: "card-body" }, [
                            comment.product ? (openBlock(), createBlock("div", { key: 0 }, toDisplayString(comment.product.name) + "へのコメント投稿 ", 1)) : createCommentVNode("", true),
                            createVNode("h2", { class: "card-title" }, toDisplayString(comment.title), 1),
                            createVNode("p", null, toDisplayString(comment.content), 1),
                            createVNode("div", { class: "card-actions justify-end" }, [
                              createTextVNode(toDisplayString(comment.created_at) + " ", 1),
                              comment.product ? (openBlock(), createBlock(unref(Link), {
                                key: 0,
                                href: _ctx.route("product.show", { id: comment.product.id }),
                                class: "btn btn-sm"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode("詳細")
                                ]),
                                _: 2
                              }, 1032, ["href"])) : createCommentVNode("", true),
                              createVNode("button", {
                                onClick: ($event) => deleteComment(comment.id),
                                class: "btn btn-sm"
                              }, "削除", 8, ["onClick"])
                            ])
                          ])
                        ]);
                      }), 128))
                    ])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, " コメント投稿はありません "))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/CommentIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
