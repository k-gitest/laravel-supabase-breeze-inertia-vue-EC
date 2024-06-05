import { defineComponent, ref, unref, withCtx, createVNode, withModifiers, withDirectives, vModelText, openBlock, createBlock, Fragment, renderList, toDisplayString, createCommentVNode, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList, ssrRenderStyle, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ContactForm",
  __ssrInlineRender: true,
  setup(__props) {
    var _a;
    const page = usePage();
    const contacts = (_a = page.props) == null ? void 0 : _a.data;
    const supabaseURL = "https://rjxtqhgniojmrcynyqkh.supabase.co/storage/v1/object/public/";
    const fileImageElement = ref(null);
    const form = useForm({
      name: "",
      email: "",
      message: "",
      image: [],
      path: ""
    });
    const submit = () => {
      form.post(route("contact.store"), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: (response) => {
          form.reset("name", "email", "message", "image", "path");
          if (fileImageElement.value) {
            fileImageElement.value.value = "";
          }
        },
        onFinish: () => {
          form.reset();
        }
      });
    };
    const handleFileChange = (event) => {
      const target = event.target;
      const file = target.files[0];
      if (file) {
        form.image.push(file);
      }
    };
    const clickHandleFile = (event) => {
      var _a2;
      event.preventDefault();
      (_a2 = fileImageElement.value) == null ? void 0 : _a2.click();
    };
    const handleFileDelete = (event) => {
      const target = event.target;
      form.image = form.image.filter((file) => file.name !== target.id);
      if (fileImageElement.value) {
        fileImageElement.value.value = "";
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Contact" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Contact</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Contact")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<main class="flex flex-col items-center justify-center w-full"${_scopeId}><h1 class="text-4xl font-bold p-2"${_scopeId}>Contact</h1><form enctype="multipart/form-data"${_scopeId}><div class="flex flex-col items-center justify-center w-full max-w-md gap-2"${_scopeId}><div${_scopeId}><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div${_scopeId}><input type="email" id="email"${ssrRenderAttr("value", unref(form).email)} class="border"${_scopeId}></div><div${_scopeId}><textarea type="text" id="message" class="border"${_scopeId}>${ssrInterpolate(unref(form).message)}</textarea></div><div${_scopeId}><input type="text" id="path"${ssrRenderAttr("value", unref(form).path)} class="border"${_scopeId}></div><div${_scopeId}><input type="file" id="image" class="hidden" accept="image/*"${_scopeId}><button${_scopeId}>ファイルを選択</button>`);
            if (unref(form).image.length > 0) {
              _push2(`<div${_scopeId}><!--[-->`);
              ssrRenderList(unref(form).image, (file) => {
                _push2(`<p${ssrRenderAttr("id", `${file.name}`)} class="cursor-pointer"${_scopeId}>${ssrInterpolate(file.name)}</p>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</p><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.email)}</p><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.message)}</p></div><div style="${ssrRenderStyle(unref(page).props.flash ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(page).props.flash.success)}</p></div><div${_scopeId}><button type="submit" class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>送信</button></div></div></form>`);
            if (unref(contacts)) {
              _push2(`<div${_scopeId}><!--[-->`);
              ssrRenderList(unref(contacts), (contact) => {
                _push2(`<div${_scopeId}><p${_scopeId}>${ssrInterpolate(contact.id)}</p><p${_scopeId}>${ssrInterpolate(contact.name)}</p><p${_scopeId}>${ssrInterpolate(contact.email)}</p><p${_scopeId}>${ssrInterpolate(contact.message)}</p><p${_scopeId}>${ssrInterpolate(contact.created_at)}</p><p${_scopeId}>${ssrInterpolate(contact.updated_at)}</p>`);
                if (contact.attachments) {
                  _push2(`<div${_scopeId}><!--[-->`);
                  ssrRenderList(contact.attachments, (file) => {
                    _push2(`<img${ssrRenderAttr("src", supabaseURL + file.key)}${_scopeId}>`);
                  });
                  _push2(`<!--]--></div>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`</div>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<div${_scopeId}><p${_scopeId}>お問い合わせ履歴はありません</p></div>`);
            }
            _push2(`</main>`);
          } else {
            return [
              createVNode("main", { class: "flex flex-col items-center justify-center w-full" }, [
                createVNode("h1", { class: "text-4xl font-bold p-2" }, "Contact"),
                createVNode("form", {
                  onSubmit: withModifiers(submit, ["prevent"]),
                  enctype: "multipart/form-data"
                }, [
                  createVNode("div", { class: "flex flex-col items-center justify-center w-full max-w-md gap-2" }, [
                    createVNode("div", null, [
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "name",
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).name]
                      ])
                    ]),
                    createVNode("div", null, [
                      withDirectives(createVNode("input", {
                        type: "email",
                        id: "email",
                        "onUpdate:modelValue": ($event) => unref(form).email = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).email]
                      ])
                    ]),
                    createVNode("div", null, [
                      withDirectives(createVNode("textarea", {
                        type: "text",
                        id: "message",
                        "onUpdate:modelValue": ($event) => unref(form).message = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).message]
                      ])
                    ]),
                    createVNode("div", null, [
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "path",
                        "onUpdate:modelValue": ($event) => unref(form).path = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).path]
                      ])
                    ]),
                    createVNode("div", null, [
                      createVNode("input", {
                        type: "file",
                        id: "image",
                        ref_key: "fileImageElement",
                        ref: fileImageElement,
                        class: "hidden",
                        accept: "image/*",
                        onInput: handleFileChange
                      }, null, 544),
                      createVNode("button", { onClick: clickHandleFile }, "ファイルを選択"),
                      unref(form).image.length > 0 ? (openBlock(), createBlock("div", { key: 0 }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(unref(form).image, (file) => {
                          return openBlock(), createBlock("p", {
                            key: file.name,
                            id: `${file.name}`,
                            onClick: handleFileDelete,
                            class: "cursor-pointer"
                          }, toDisplayString(file.name), 9, ["id"]);
                        }), 128))
                      ])) : createCommentVNode("", true)
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.name), 1),
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.email), 1),
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.message), 1)
                    ], 512), [
                      [vShow, unref(form).errors]
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(page).props.flash.success), 1)
                    ], 512), [
                      [vShow, unref(page).props.flash]
                    ]),
                    createVNode("div", null, [
                      createVNode("button", {
                        type: "submit",
                        class: "btn",
                        disabled: unref(form).processing
                      }, "送信", 8, ["disabled"])
                    ])
                  ])
                ], 32),
                unref(contacts) ? (openBlock(), createBlock("div", { key: 0 }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(unref(contacts), (contact) => {
                    return openBlock(), createBlock("div", {
                      key: contact.id
                    }, [
                      createVNode("p", null, toDisplayString(contact.id), 1),
                      createVNode("p", null, toDisplayString(contact.name), 1),
                      createVNode("p", null, toDisplayString(contact.email), 1),
                      createVNode("p", null, toDisplayString(contact.message), 1),
                      createVNode("p", null, toDisplayString(contact.created_at), 1),
                      createVNode("p", null, toDisplayString(contact.updated_at), 1),
                      contact.attachments ? (openBlock(), createBlock("div", { key: 0 }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(contact.attachments, (file) => {
                          return openBlock(), createBlock("img", {
                            key: file.id,
                            src: supabaseURL + file.key
                          }, null, 8, ["src"]);
                        }), 128))
                      ])) : createCommentVNode("", true)
                    ]);
                  }), 128))
                ])) : (openBlock(), createBlock("div", { key: 1 }, [
                  createVNode("p", null, "お問い合わせ履歴はありません")
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Contact/ContactForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
