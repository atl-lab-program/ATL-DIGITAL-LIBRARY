const fs = require('fs');
const path = require('path');

// Read books data
const books = JSON.parse(fs.readFileSync('books.json', 'utf8'));

// Read HTML template
const template = fs.readFileSync('template.html', 'utf8');

// Function to replace {{ variable }} or {{variable}}
function replaceVariable(html, variable, value) {
    const regex = new RegExp(`{{\\s*${variable}\\s*}}`, 'g');
    return html.replace(regex, value ?? '');
}

// Generate one HTML file per book
books.forEach(book => {

    let htmlContent = template;

    htmlContent = replaceVariable(htmlContent, 'title', book.title);
    htmlContent = replaceVariable(htmlContent, 'author', book.author);
    htmlContent = replaceVariable(htmlContent, 'cover', book.cover);
    htmlContent = replaceVariable(htmlContent, 'description', book.description);

    // --- Dynamic Series Section Logic ---
    // If genre is 'ack', id is 'ack', or seriesBooksHtml is empty, omit the section completely.
    let seriesSection = '';
    const isAck = book.genre === 'ack' || book.id === 'ack';

    if (!isAck && book.seriesBooksHtml && book.seriesBooksHtml.trim() !== '') {
        const hasImageRow = book.seriesBooksHtml.includes('image-row');
        
        seriesSection = `
        <h2>Books in the Series</h2>
        ${hasImageRow ? book.seriesBooksHtml : `<div class="image-row">\n${book.seriesBooksHtml}\n</div>`}
        `.trim();
    }

    htmlContent = replaceVariable(htmlContent, 'seriesSection', seriesSection);

    htmlContent = replaceVariable(
        htmlContent,
        'fontFamily',
        book.fontFamily || 'sans-serif'
    );

    htmlContent = replaceVariable(
        htmlContent,
        'fontWeight',
        book.fontWeight || 'normal'
    );

    htmlContent = replaceVariable(
        htmlContent,
        'bgGradient',
        book.bgGradient || '#ffffff'
    );

    htmlContent = replaceVariable(
        htmlContent,
        'fontImport',
        book.fontImport || ''
    );

    // Create output folder
    const outputDir = path.join('books', book.genre);
    fs.mkdirSync(outputDir, { recursive: true });

    // Save HTML file
    fs.writeFileSync(
        path.join(outputDir, `${book.id}.html`),
        htmlContent,
        'utf8'
    );

    console.log(`✓ Generated ${book.id}.html`);
});

console.log(`\n🎉 Successfully generated ${books.length} HTML pages! yayy!!`);
console.log('📁 Output folder: books ');
