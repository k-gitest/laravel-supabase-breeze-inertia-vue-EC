import { defineComponent, ref, computed, unref, withCtx, createVNode, openBlock, createBlock, withModifiers, Fragment, renderList, toDisplayString, createCommentVNode, withDirectives, vModelText, vModelSelect, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderStyle, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductRegister",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const fileImageElement = ref(null);
    const form = useForm({
      name: "",
      description: "",
      price_excluding_tax: 0,
      tax_rate: 0,
      category_id: "",
      image: []
    });
    let preview = [];
    const submit = () => {
      console.log(form);
      form.post(route("admin.product.store"), {
        onSuccess: (res) => {
          console.log(res);
        }
      });
    };
    const price_including_tax = computed(() => {
      return (form.price_excluding_tax * (1 + form.tax_rate / 100)).toFixed(2);
    });
    const handleFileChange = (event) => {
      const target = event.target;
      const file = target.files[0];
      if (file) {
        form.image.push(file);
        console.log(form.image);
        const blob = URL.createObjectURL(file);
        preview.push({ name: file.name, url: blob });
        setTimeout(() => {
          URL.revokeObjectURL(blob);
        }, 2e3);
      }
    };
    const clickHandleFile = (event) => {
      var _a;
      event.preventDefault();
      (_a = fileImageElement.value) == null ? void 0 : _a.click();
    };
    const handleFileDelete = (event) => {
      const currentTarget = event.currentTarget;
      form.image = form.image.filter((file) => file.name !== currentTarget.id);
      preview = preview.filter((file) => file.name !== currentTarget.id);
      if (fileImageElement.value) {
        fileImageElement.value.value = "";
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "ProductRegister" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Product Register</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Product Register")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (unref(props).data.length === 0) {
              _push2(`<div${_scopeId}><p${_scopeId}>カテゴリーが登録されていません</p></div>`);
            } else {
              _push2(`<form enctype="multipart/form-data"${_scopeId}><div class="flex gap-2"${_scopeId}><div class="flex flex-col"${_scopeId}><label for="image"${_scopeId}>商品画像</label><input type="file" id="image" class="hidden" accept="image/*"${_scopeId}><button class="btn"${_scopeId}>ファイルを選択</button></div>`);
              if (unref(form).image.length > 0) {
                _push2(`<div class="flex gap-2"${_scopeId}><!--[-->`);
                ssrRenderList(unref(form).image, (file, index) => {
                  _push2(`<div${ssrRenderAttr("id", `${file.name}`)} class="max-w-32 flex flex-col justify-between items-center cursor-pointer"${_scopeId}><img${ssrRenderAttr("src", unref(preview)[index].url)} class="max-w-32"${_scopeId}><p class="cursor-pointer"${_scopeId}>${ssrInterpolate(file.name)}</p></div>`);
                });
                _push2(`<!--]--></div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div class="flex flex-col items-center justify-center w-full max-w-md gap-2"${_scopeId}><div class="flex flex-col"${_scopeId}><label for="name"${_scopeId}>商品名</label><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="description"${_scopeId}>商品の説明</label><textarea type="text" id="description" class="border"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea></div>`);
              if (unref(props).data.length) {
                _push2(`<div class="flex flex-col"${_scopeId}><label for="category"${_scopeId}>カテゴリ</label><select id="category" class="select-sm border w-full max-w-xs"${_scopeId}><option disabled selected${_scopeId}>選択してください</option><!--[-->`);
                ssrRenderList(unref(props).data, (category) => {
                  _push2(`<option${ssrRenderAttr("value", category.id)}${_scopeId}>${ssrInterpolate(category.name)}</option>`);
                });
                _push2(`<!--]--></select></div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div class="flex flex-col"${_scopeId}><label for="price_excluding_tax"${_scopeId}>税抜き価格</label><input type="text" id="price_excluding_tax"${ssrRenderAttr("value", unref(form).price_excluding_tax)} class="border"${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="price_including_tax"${_scopeId}>税込み価格</label><input type="text" id="price_including_tax"${ssrRenderAttr("value", price_including_tax.value)} class="border" disabled${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="tax_rate"${_scopeId}>税率</label><div clas="flex"${_scopeId}><input type="text" id="tax_rate"${ssrRenderAttr("value", unref(form).tax_rate)} class="border grow"${_scopeId}><span class="w-full"${_scopeId}>%</span></div></div><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</p><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</p></div><div style="${ssrRenderStyle(unref(props).flash ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div><div${_scopeId}><button type="submit" class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>送信</button></div></div></div></form>`);
            }
          } else {
            return [
              unref(props).data.length === 0 ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("p", null, "カテゴリーが登録されていません")
              ])) : (openBlock(), createBlock("form", {
                key: 1,
                onSubmit: withModifiers(submit, ["prevent"]),
                enctype: "multipart/form-data"
              }, [
                createVNode("div", { class: "flex gap-2" }, [
                  createVNode("div", { class: "flex flex-col" }, [
                    createVNode("label", { for: "image" }, "商品画像"),
                    createVNode("input", {
                      type: "file",
                      id: "image",
                      ref_key: "fileImageElement",
                      ref: fileImageElement,
                      class: "hidden",
                      accept: "image/*",
                      onInput: handleFileChange
                    }, null, 544),
                    createVNode("button", {
                      onClick: clickHandleFile,
                      class: "btn"
                    }, "ファイルを選択")
                  ]),
                  unref(form).image.length > 0 ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "flex gap-2"
                  }, [
                    (openBlock(true), createBlock(Fragment, null, renderList(unref(form).image, (file, index) => {
                      return openBlock(), createBlock("div", {
                        key: file.name,
                        id: `${file.name}`,
                        onClick: handleFileDelete,
                        class: "max-w-32 flex flex-col justify-between items-center cursor-pointer"
                      }, [
                        createVNode("img", {
                          src: unref(preview)[index].url,
                          class: "max-w-32"
                        }, null, 8, ["src"]),
                        createVNode("p", { class: "cursor-pointer" }, toDisplayString(file.name), 1)
                      ], 8, ["id"]);
                    }), 128))
                  ])) : createCommentVNode("", true),
                  createVNode("div", { class: "flex flex-col items-center justify-center w-full max-w-md gap-2" }, [
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "name" }, "商品名"),
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "name",
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).name]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "description" }, "商品の説明"),
                      withDirectives(createVNode("textarea", {
                        type: "text",
                        id: "description",
                        "onUpdate:modelValue": ($event) => unref(form).description = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).description]
                      ])
                    ]),
                    unref(props).data.length ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "flex flex-col"
                    }, [
                      createVNode("label", { for: "category" }, "カテゴリ"),
                      withDirectives(createVNode("select", {
                        id: "category",
                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                        class: "select-sm border w-full max-w-xs"
                      }, [
                        createVNode("option", {
                          disabled: "",
                          selected: ""
                        }, "選択してください"),
                        (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (category) => {
                          return openBlock(), createBlock("option", {
                            key: category.id,
                            value: category.id
                          }, toDisplayString(category.name), 9, ["value"]);
                        }), 128))
                      ], 8, ["onUpdate:modelValue"]), [
                        [vModelSelect, unref(form).category_id]
                      ])
                    ])) : createCommentVNode("", true),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "price_excluding_tax" }, "税抜き価格"),
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "price_excluding_tax",
                        "onUpdate:modelValue": ($event) => unref(form).price_excluding_tax = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).price_excluding_tax]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "price_including_tax" }, "税込み価格"),
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "price_including_tax",
                        "onUpdate:modelValue": ($event) => price_including_tax.value = $event,
                        class: "border",
                        disabled: ""
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, price_including_tax.value]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "tax_rate" }, "税率"),
                      createVNode("div", { clas: "flex" }, [
                        withDirectives(createVNode("input", {
                          type: "text",
                          id: "tax_rate",
                          "onUpdate:modelValue": ($event) => unref(form).tax_rate = $event,
                          class: "border grow"
                        }, null, 8, ["onUpdate:modelValue"]), [
                          [vModelText, unref(form).tax_rate]
                        ]),
                        createVNode("span", { class: "w-full" }, "%")
                      ])
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.name), 1),
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.description), 1)
                    ], 512), [
                      [vShow, unref(form).errors]
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                    ], 512), [
                      [vShow, unref(props).flash]
                    ]),
                    createVNode("div", null, [
                      createVNode("button", {
                        type: "submit",
                        class: "btn",
                        disabled: unref(form).processing
                      }, "送信", 8, ["disabled"])
                    ])
                  ])
                ])
              ], 32))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/ProductRegister.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
