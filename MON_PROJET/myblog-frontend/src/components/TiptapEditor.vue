<template>
  <div class="tiptap-editor">
    <!-- Menu toolbar -->
    <div v-if="editor" class="menu-bar bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-t-lg p-2 flex flex-wrap gap-1">
      <!-- Bold -->
      <button
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'is-active': editor.isActive('bold') }"
        class="menu-button"
        type="button"
        title="Gras (Ctrl+B)"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M12.5 4h-5v12h5a3 3 0 100-6 3 3 0 000-6zm-2 5a2 2 0 110-4 2 2 0 010 4zm0 7a2 2 0 110-4 2 2 0 010 4z"/>
        </svg>
      </button>

      <!-- Italic -->
      <button
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'is-active': editor.isActive('italic') }"
        class="menu-button"
        type="button"
        title="Italique (Ctrl+I)"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M8 3h8v2h-3l-2 10h3v2H6v-2h3l2-10H8V3z"/>
        </svg>
      </button>

      <!-- Underline -->
      <button
        @click="editor.chain().focus().toggleUnderline().run()"
        :class="{ 'is-active': editor.isActive('underline') }"
        class="menu-button"
        type="button"
        title="Souligné (Ctrl+U)"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 18a5 5 0 01-5-5V3h2v10a3 3 0 006 0V3h2v10a5 5 0 01-5 5zM3 18h14v2H3v-2z"/>
        </svg>
      </button>

      <div class="divider"></div>

      <!-- Headings -->
      <button
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
        class="menu-button"
        type="button"
        title="Titre 2"
      >
        H2
      </button>

      <button
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
        class="menu-button"
        type="button"
        title="Titre 3"
      >
        H3
      </button>

      <div class="divider"></div>

      <!-- Bullet List -->
      <button
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'is-active': editor.isActive('bulletList') }"
        class="menu-button"
        type="button"
        title="Liste à puces"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 100 2 1 1 0 000-2zm4 1h10v1H7V5zm-4 5a1 1 0 100 2 1 1 0 000-2zm4 1h10v1H7v-1zm-4 5a1 1 0 100 2 1 1 0 000-2zm4 1h10v1H7v-1z"/>
        </svg>
      </button>

      <!-- Ordered List -->
      <button
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'is-active': editor.isActive('orderedList') }"
        class="menu-button"
        type="button"
        title="Liste numérotée"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 3v2h1v1H3v1h2V6h1V3H3zm0 7v1h1v1H3v1h2v-1h1v-3H3zm0 7v1h1v1H3v1h3v-1H5v-1h1v-1H3zM8 4h10v1H8V4zm0 6h10v1H8v-1zm0 6h10v1H8v-1z"/>
        </svg>
      </button>

      <div class="divider"></div>

      <!-- Blockquote -->
      <button
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'is-active': editor.isActive('blockquote') }"
        class="menu-button"
        type="button"
        title="Citation"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M6 6h3v3H6V6zm5 0h3v3h-3V6zM3 9h3v8H3V9zm5 0h3v8H8V9z"/>
        </svg>
      </button>

      <!-- Code Block -->
      <button
        @click="editor.chain().focus().toggleCodeBlock().run()"
        :class="{ 'is-active': editor.isActive('codeBlock') }"
        class="menu-button"
        type="button"
        title="Code"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M7.05 9.293L3.707 5.95 2.293 7.364 7.05 12.12l1.414-1.414L7.05 9.293zM15.95 9.293l-1.414-1.414-3.343 3.343 1.414 1.414 3.343-3.343zm-4.243-4.95l-7.071 7.071 1.414 1.414 7.071-7.071-1.414-1.414z"/>
        </svg>
      </button>

      <div class="divider"></div>

      <!-- Alignment -->
      <button
        @click="editor.chain().focus().setTextAlign('left').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
        class="menu-button"
        type="button"
        title="Aligner à gauche"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4h14v2H3V4zm0 4h10v2H3V8zm0 4h14v2H3v-2zm0 4h10v2H3v-2z"/>
        </svg>
      </button>

      <button
        @click="editor.chain().focus().setTextAlign('center').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
        class="menu-button"
        type="button"
        title="Centrer"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4h14v2H3V4zm3 4h8v2H6V8zm-3 4h14v2H3v-2zm3 4h8v2H6v-2z"/>
        </svg>
      </button>

      <button
        @click="editor.chain().focus().setTextAlign('right').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
        class="menu-button"
        type="button"
        title="Aligner à droite"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4h14v2H3V4zm4 4h10v2H7V8zm-4 4h14v2H3v-2zm4 4h10v2H7v-2z"/>
        </svg>
      </button>

      <div class="divider"></div>

      <!-- Undo/Redo -->
      <button
        @click="editor.chain().focus().undo().run()"
        :disabled="!editor.can().undo()"
        class="menu-button"
        type="button"
        title="Annuler (Ctrl+Z)"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M8 3v2H4a1 1 0 00-1 1v8a1 1 0 001 1h8a1 1 0 001-1v-4h2v4a3 3 0 01-3 3H4a3 3 0 01-3-3V6a3 3 0 013-3h4zm4 0l4 4-4 4v-3H8V6h4V3z"/>
        </svg>
      </button>

      <button
        @click="editor.chain().focus().redo().run()"
        :disabled="!editor.can().redo()"
        class="menu-button"
        type="button"
        title="Rétablir (Ctrl+Y)"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M12 3v2h4a1 1 0 011 1v8a1 1 0 01-1 1H8a1 1 0 01-1-1v-4H5v4a3 3 0 003 3h8a3 3 0 003-3V6a3 3 0 00-3-3h-4zm-4 0L4 7l4 4V8h4V6H8V3z"/>
        </svg>
      </button>
    </div>

    <!-- Editor content -->
    <editor-content 
      :editor="editor" 
      class="editor-content border border-t-0 border-gray-300 dark:border-gray-600 rounded-b-lg p-4 min-h-[300px] bg-white dark:bg-gray-800 text-gray-900 dark:text-white prose dark:prose-invert max-w-none"
    />
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import TextAlign from '@tiptap/extension-text-align'
import Placeholder from '@tiptap/extension-placeholder'
import { watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Écrivez votre article ici...'
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Underline,
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
    Placeholder.configure({
      placeholder: props.placeholder,
    }),
  ],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})

// Synchroniser le contenu quand modelValue change
watch(() => props.modelValue, (value) => {
  if (!editor.value) return
  const isSame = editor.value.getHTML() === value
  if (!isSame) {
    editor.value.commands.setContent(value, false)
  }
})
</script>

<style scoped>
.menu-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
}

.menu-button {
  @apply px-2 py-1.5 rounded bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-500 transition;
  border: 1px solid #e5e7eb;
  font-weight: 600;
  font-size: 0.875rem;
}

.menu-button:disabled {
  @apply opacity-50 cursor-not-allowed;
}

.menu-button.is-active {
  @apply bg-indigo-600 text-white border-indigo-600;
}

.divider {
  width: 1px;
  height: 1.5rem;
  @apply bg-gray-300 dark:bg-gray-600 self-center;
}

/* Styles pour le contenu de l'éditeur */
:deep(.ProseMirror) {
  outline: none;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}

:deep(.ProseMirror) h2 {
  @apply text-2xl font-bold mt-6 mb-4;
}

:deep(.ProseMirror) h3 {
  @apply text-xl font-bold mt-4 mb-3;
}

:deep(.ProseMirror) blockquote {
  @apply border-l-4 border-gray-300 dark:border-gray-600 pl-4 italic my-4;
}

:deep(.ProseMirror) code {
  @apply bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded text-sm;
}

:deep(.ProseMirror) pre {
  @apply bg-gray-900 text-gray-100 p-4 rounded-lg my-4 overflow-x-auto;
}

:deep(.ProseMirror) pre code {
  @apply bg-transparent p-0;
}

:deep(.ProseMirror) ul,
:deep(.ProseMirror) ol {
  @apply pl-6 my-4;
}

:deep(.ProseMirror) li {
  @apply my-1;
}
</style>