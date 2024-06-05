import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, toDisplayString, createCommentVNode, withModifiers, withDirectives, vModelText, Fragment, renderList, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { _ as _sfc_main$3 } from "./EcImageGallery-BXIFEkZy.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductDetail",
  __ssrInlineRender: true,
  setup(__props) {
    var _a, _b;
    const { props } = usePage();
    const form = useForm({
      product_id: props.data.id,
      user_id: (_b = (_a = props == null ? void 0 : props.auth) == null ? void 0 : _a.user) == null ? void 0 : _b.id,
      quantity: 1
    });
    const commentForm = useForm({
      product_id: props.data.id,
      comment: "",
      title: ""
    });
    useForm({
      product_id: props.data.id
    });
    const submit = () => {
      form.post(route("cart.store"), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    const commentSubmit = () => {
      commentForm.post(route("comment.store"), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    const favoriteSubmit = () => {
      form.post(route("favorite.store"), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "ProductAllList" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>ProductDetail</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "ProductDetail")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                var _a2, _b2;
                if (_push3) {
                  if (unref(props).data) {
                    _push3(`<div class="flex gap-2"${_scopeId2}>`);
                    _push3(ssrRenderComponent(_sfc_main$3, {
                      images: unref(props).data.image
                    }, null, _parent3, _scopeId2));
                    _push3(`<div${_scopeId2}><ul${_scopeId2}><li${_scopeId2}>${ssrInterpolate(unref(props).data.name)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.description)}</li><li${_scopeId2}>${ssrInterpolate((_a2 = unref(props).data.category) == null ? void 0 : _a2.name)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.price_excluding_tax)}</li><li${_scopeId2}>${ssrInterpolate(unref(props).data.price_including_tax)}</li></ul>`);
                    if (unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0 && unref(props).data.stock_sum_quantity < 5) {
                      _push3(`<div${_scopeId2}> 在庫数：残り僅か </div>`);
                    } else if (unref(props).data.stock_sum_quantity === 0 || !unref(props).data.stock_sum_quantity) {
                      _push3(`<div${_scopeId2}><p class="text-red-400"${_scopeId2}>売り切れ</p></div>`);
                    } else {
                      _push3(`<!---->`);
                    }
                    if (unref(props).auth.user) {
                      _push3(`<div class="flex flex-col gap-2"${_scopeId2}>`);
                      if (unref(props).isInCart) {
                        _push3(`<div${_scopeId2}><button class="btn" disabled${_scopeId2}>カートに追加済み</button></div>`);
                      } else {
                        _push3(`<div${_scopeId2}><form${_scopeId2}><div class="flex flex-col gap-2"${_scopeId2}><label for="quantity"${_scopeId2}>数量 <input type="number" min="1" max="99" id="quantity"${ssrRenderAttr("value", unref(form).quantity)}${_scopeId2}></label><button class="btn"${_scopeId2}>カートに追加</button></div></form></div>`);
                      }
                      if (unref(props).isInFavorite) {
                        _push3(`<div${_scopeId2}><button class="btn" disabled${_scopeId2}>お気に入りに追加済み</button></div>`);
                      } else {
                        _push3(`<div${_scopeId2}><form${_scopeId2}><button type="submit" class="btn w-full"${_scopeId2}>お気に入りに追加</button></form></div>`);
                      }
                      if (unref(props).isInComment) {
                        _push3(`<div${_scopeId2}><p${_scopeId2}>既にコメントを投稿しています</p></div>`);
                      } else {
                        _push3(`<div${_scopeId2}><form${_scopeId2}><div class="flex flex-col gap-2"${_scopeId2}><p${_scopeId2}>コメントを投稿する</p><input${ssrRenderAttr("value", unref(commentForm).title)} type="text" placeholder="タイトル" class="input input-bordered input-sm w-full max-w-xs"${_scopeId2}><textarea class="textarea textarea-bordered" placeholder="コメント"${_scopeId2}>${ssrInterpolate(unref(commentForm).comment)}</textarea><button type="submit" class="btn"${_scopeId2}>投稿</button></div></form></div>`);
                      }
                      if (unref(props).data.comment && unref(props).data.comment.length) {
                        _push3(`<div${_scopeId2}><!--[-->`);
                        ssrRenderList(unref(props).data.comment, (comment) => {
                          _push3(`<div class="card w-96 bg-base-100 border"${_scopeId2}><div class="card-body"${_scopeId2}><h2 class="card-title"${_scopeId2}>${ssrInterpolate(comment.title)}</h2><p${_scopeId2}>${ssrInterpolate(comment.content)}</p><div class="card-actions justify-end"${_scopeId2}>${ssrInterpolate(comment.created_at)}</div></div></div>`);
                        });
                        _push3(`<!--]--></div>`);
                      } else {
                        _push3(`<div${_scopeId2}> コメント投稿はありません </div>`);
                      }
                      _push3(`<div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId2}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId2}>${ssrInterpolate(unref(form).errors.quantity)} ${ssrInterpolate(unref(form).errors.user_id)} ${ssrInterpolate(unref(form).errors.product_id)}</p></div><div style="${ssrRenderStyle(unref(props).errors ? null : { display: "none" })}"${_scopeId2}>${ssrInterpolate(unref(props).errors.quantity)} ${ssrInterpolate(unref(props).errors.user_id)} ${ssrInterpolate(unref(props).errors.product_id)}</div></div>`);
                    } else {
                      _push3(`<div${_scopeId2}>`);
                      if (unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0) {
                        _push3(`<div${_scopeId2}>`);
                        _push3(ssrRenderComponent(unref(Link), {
                          class: "btn",
                          href: _ctx.route("login")
                        }, {
                          default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                            if (_push4) {
                              _push4(`ログインして購入`);
                            } else {
                              return [
                                createTextVNode("ログインして購入")
                              ];
                            }
                          }),
                          _: 1
                        }, _parent3, _scopeId2));
                        _push3(`</div>`);
                      } else {
                        _push3(`<!---->`);
                      }
                      _push3(`</div>`);
                    }
                    _push3(`</div></div>`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    unref(props).data ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex gap-2"
                    }, [
                      createVNode(_sfc_main$3, {
                        images: unref(props).data.image
                      }, null, 8, ["images"]),
                      createVNode("div", null, [
                        createVNode("ul", null, [
                          createVNode("li", null, toDisplayString(unref(props).data.name), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.description), 1),
                          createVNode("li", null, toDisplayString((_b2 = unref(props).data.category) == null ? void 0 : _b2.name), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.price_excluding_tax), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.price_including_tax), 1)
                        ]),
                        unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0 && unref(props).data.stock_sum_quantity < 5 ? (openBlock(), createBlock("div", { key: 0 }, " 在庫数：残り僅か ")) : unref(props).data.stock_sum_quantity === 0 || !unref(props).data.stock_sum_quantity ? (openBlock(), createBlock("div", { key: 1 }, [
                          createVNode("p", { class: "text-red-400" }, "売り切れ")
                        ])) : createCommentVNode("", true),
                        unref(props).auth.user ? (openBlock(), createBlock("div", {
                          key: 2,
                          class: "flex flex-col gap-2"
                        }, [
                          unref(props).isInCart ? (openBlock(), createBlock("div", { key: 0 }, [
                            createVNode("button", {
                              class: "btn",
                              disabled: ""
                            }, "カートに追加済み")
                          ])) : (openBlock(), createBlock("div", { key: 1 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(submit, ["prevent"])
                            }, [
                              createVNode("div", { class: "flex flex-col gap-2" }, [
                                createVNode("label", { for: "quantity" }, [
                                  createTextVNode("数量 "),
                                  withDirectives(createVNode("input", {
                                    type: "number",
                                    min: "1",
                                    max: "99",
                                    id: "quantity",
                                    "onUpdate:modelValue": ($event) => unref(form).quantity = $event
                                  }, null, 8, ["onUpdate:modelValue"]), [
                                    [vModelText, unref(form).quantity]
                                  ])
                                ]),
                                createVNode("button", { class: "btn" }, "カートに追加")
                              ])
                            ], 32)
                          ])),
                          unref(props).isInFavorite ? (openBlock(), createBlock("div", { key: 2 }, [
                            createVNode("button", {
                              class: "btn",
                              disabled: ""
                            }, "お気に入りに追加済み")
                          ])) : (openBlock(), createBlock("div", { key: 3 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(favoriteSubmit, ["prevent"])
                            }, [
                              createVNode("button", {
                                type: "submit",
                                class: "btn w-full"
                              }, "お気に入りに追加")
                            ], 32)
                          ])),
                          unref(props).isInComment ? (openBlock(), createBlock("div", { key: 4 }, [
                            createVNode("p", null, "既にコメントを投稿しています")
                          ])) : (openBlock(), createBlock("div", { key: 5 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(commentSubmit, ["prevent"])
                            }, [
                              createVNode("div", { class: "flex flex-col gap-2" }, [
                                createVNode("p", null, "コメントを投稿する"),
                                withDirectives(createVNode("input", {
                                  "onUpdate:modelValue": ($event) => unref(commentForm).title = $event,
                                  type: "text",
                                  placeholder: "タイトル",
                                  class: "input input-bordered input-sm w-full max-w-xs"
                                }, null, 8, ["onUpdate:modelValue"]), [
                                  [vModelText, unref(commentForm).title]
                                ]),
                                withDirectives(createVNode("textarea", {
                                  "onUpdate:modelValue": ($event) => unref(commentForm).comment = $event,
                                  class: "textarea textarea-bordered",
                                  placeholder: "コメント"
                                }, null, 8, ["onUpdate:modelValue"]), [
                                  [vModelText, unref(commentForm).comment]
                                ]),
                                createVNode("button", {
                                  type: "submit",
                                  class: "btn"
                                }, "投稿")
                              ])
                            ], 32)
                          ])),
                          unref(props).data.comment && unref(props).data.comment.length ? (openBlock(), createBlock("div", { key: 6 }, [
                            (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data.comment, (comment) => {
                              return openBlock(), createBlock("div", {
                                key: comment.id,
                                class: "card w-96 bg-base-100 border"
                              }, [
                                createVNode("div", { class: "card-body" }, [
                                  createVNode("h2", { class: "card-title" }, toDisplayString(comment.title), 1),
                                  createVNode("p", null, toDisplayString(comment.content), 1),
                                  createVNode("div", { class: "card-actions justify-end" }, toDisplayString(comment.created_at), 1)
                                ])
                              ]);
                            }), 128))
                          ])) : (openBlock(), createBlock("div", { key: 7 }, " コメント投稿はありません ")),
                          withDirectives(createVNode("div", null, [
                            createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.quantity) + " " + toDisplayString(unref(form).errors.user_id) + " " + toDisplayString(unref(form).errors.product_id), 1)
                          ], 512), [
                            [vShow, unref(form).errors]
                          ]),
                          withDirectives(createVNode("div", null, toDisplayString(unref(props).errors.quantity) + " " + toDisplayString(unref(props).errors.user_id) + " " + toDisplayString(unref(props).errors.product_id), 513), [
                            [vShow, unref(props).errors]
                          ])
                        ])) : (openBlock(), createBlock("div", { key: 3 }, [
                          unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0 ? (openBlock(), createBlock("div", { key: 0 }, [
                            createVNode(unref(Link), {
                              class: "btn",
                              href: _ctx.route("login")
                            }, {
                              default: withCtx(() => [
                                createTextVNode("ログインして購入")
                              ]),
                              _: 1
                            }, 8, ["href"])
                          ])) : createCommentVNode("", true)
                        ]))
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
                default: withCtx(() => {
                  var _a2;
                  return [
                    unref(props).data ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex gap-2"
                    }, [
                      createVNode(_sfc_main$3, {
                        images: unref(props).data.image
                      }, null, 8, ["images"]),
                      createVNode("div", null, [
                        createVNode("ul", null, [
                          createVNode("li", null, toDisplayString(unref(props).data.name), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.description), 1),
                          createVNode("li", null, toDisplayString((_a2 = unref(props).data.category) == null ? void 0 : _a2.name), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.price_excluding_tax), 1),
                          createVNode("li", null, toDisplayString(unref(props).data.price_including_tax), 1)
                        ]),
                        unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0 && unref(props).data.stock_sum_quantity < 5 ? (openBlock(), createBlock("div", { key: 0 }, " 在庫数：残り僅か ")) : unref(props).data.stock_sum_quantity === 0 || !unref(props).data.stock_sum_quantity ? (openBlock(), createBlock("div", { key: 1 }, [
                          createVNode("p", { class: "text-red-400" }, "売り切れ")
                        ])) : createCommentVNode("", true),
                        unref(props).auth.user ? (openBlock(), createBlock("div", {
                          key: 2,
                          class: "flex flex-col gap-2"
                        }, [
                          unref(props).isInCart ? (openBlock(), createBlock("div", { key: 0 }, [
                            createVNode("button", {
                              class: "btn",
                              disabled: ""
                            }, "カートに追加済み")
                          ])) : (openBlock(), createBlock("div", { key: 1 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(submit, ["prevent"])
                            }, [
                              createVNode("div", { class: "flex flex-col gap-2" }, [
                                createVNode("label", { for: "quantity" }, [
                                  createTextVNode("数量 "),
                                  withDirectives(createVNode("input", {
                                    type: "number",
                                    min: "1",
                                    max: "99",
                                    id: "quantity",
                                    "onUpdate:modelValue": ($event) => unref(form).quantity = $event
                                  }, null, 8, ["onUpdate:modelValue"]), [
                                    [vModelText, unref(form).quantity]
                                  ])
                                ]),
                                createVNode("button", { class: "btn" }, "カートに追加")
                              ])
                            ], 32)
                          ])),
                          unref(props).isInFavorite ? (openBlock(), createBlock("div", { key: 2 }, [
                            createVNode("button", {
                              class: "btn",
                              disabled: ""
                            }, "お気に入りに追加済み")
                          ])) : (openBlock(), createBlock("div", { key: 3 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(favoriteSubmit, ["prevent"])
                            }, [
                              createVNode("button", {
                                type: "submit",
                                class: "btn w-full"
                              }, "お気に入りに追加")
                            ], 32)
                          ])),
                          unref(props).isInComment ? (openBlock(), createBlock("div", { key: 4 }, [
                            createVNode("p", null, "既にコメントを投稿しています")
                          ])) : (openBlock(), createBlock("div", { key: 5 }, [
                            createVNode("form", {
                              onSubmit: withModifiers(commentSubmit, ["prevent"])
                            }, [
                              createVNode("div", { class: "flex flex-col gap-2" }, [
                                createVNode("p", null, "コメントを投稿する"),
                                withDirectives(createVNode("input", {
                                  "onUpdate:modelValue": ($event) => unref(commentForm).title = $event,
                                  type: "text",
                                  placeholder: "タイトル",
                                  class: "input input-bordered input-sm w-full max-w-xs"
                                }, null, 8, ["onUpdate:modelValue"]), [
                                  [vModelText, unref(commentForm).title]
                                ]),
                                withDirectives(createVNode("textarea", {
                                  "onUpdate:modelValue": ($event) => unref(commentForm).comment = $event,
                                  class: "textarea textarea-bordered",
                                  placeholder: "コメント"
                                }, null, 8, ["onUpdate:modelValue"]), [
                                  [vModelText, unref(commentForm).comment]
                                ]),
                                createVNode("button", {
                                  type: "submit",
                                  class: "btn"
                                }, "投稿")
                              ])
                            ], 32)
                          ])),
                          unref(props).data.comment && unref(props).data.comment.length ? (openBlock(), createBlock("div", { key: 6 }, [
                            (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data.comment, (comment) => {
                              return openBlock(), createBlock("div", {
                                key: comment.id,
                                class: "card w-96 bg-base-100 border"
                              }, [
                                createVNode("div", { class: "card-body" }, [
                                  createVNode("h2", { class: "card-title" }, toDisplayString(comment.title), 1),
                                  createVNode("p", null, toDisplayString(comment.content), 1),
                                  createVNode("div", { class: "card-actions justify-end" }, toDisplayString(comment.created_at), 1)
                                ])
                              ]);
                            }), 128))
                          ])) : (openBlock(), createBlock("div", { key: 7 }, " コメント投稿はありません ")),
                          withDirectives(createVNode("div", null, [
                            createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.quantity) + " " + toDisplayString(unref(form).errors.user_id) + " " + toDisplayString(unref(form).errors.product_id), 1)
                          ], 512), [
                            [vShow, unref(form).errors]
                          ]),
                          withDirectives(createVNode("div", null, toDisplayString(unref(props).errors.quantity) + " " + toDisplayString(unref(props).errors.user_id) + " " + toDisplayString(unref(props).errors.product_id), 513), [
                            [vShow, unref(props).errors]
                          ])
                        ])) : (openBlock(), createBlock("div", { key: 3 }, [
                          unref(props).data.stock_sum_quantity && unref(props).data.stock_sum_quantity > 0 ? (openBlock(), createBlock("div", { key: 0 }, [
                            createVNode(unref(Link), {
                              class: "btn",
                              href: _ctx.route("login")
                            }, {
                              default: withCtx(() => [
                                createTextVNode("ログインして購入")
                              ]),
                              _: 1
                            }, 8, ["href"])
                          ])) : createCommentVNode("", true)
                        ]))
                      ])
                    ])) : createCommentVNode("", true)
                  ];
                }),
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/ProductDetail.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
