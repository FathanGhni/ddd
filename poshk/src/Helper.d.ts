declare module './Helper.js' {
    const Helper: {
      /**
       * Mengonversi angka ke format mata uang IDR tanpa desimal.
       * @param val Angka yang akan diformat (default 0).
       * @returns String dalam format mata uang IDR.
       */
      formatCurrency(val?: number): string;
  
      /**
       * Mengonversi angka ke format rupiah dengan jumlah desimal yang bisa diatur.
       * @param x Angka yang akan diformat (null atau 0 mengembalikan '-').
       * @param decimal Jumlah desimal (default 2).
       * @returns String yang diformat dengan koma sebagai pemisah ribuan.
       */
      formatRp(x: number | null, decimal?: number): string;
  
      /**
       * Mengonversi tanggal dari satu format ke format lain.
       * @param dt Tanggal dalam format asli (nullable).
       * @param originalformat Format asli tanggal (default 'DD/MM/YYYY').
       * @param outputformat Format tanggal output (default 'YYYY-MM-DD').
       * @returns Tanggal dalam format output.
       */
      formatDate(
        dt: string | null, 
        originalformat?: string, 
        outputformat?: string
      ): string;
  
      /**
       * Mengambil tanggal hari ini dalam format yang ditentukan.
       * @param outputformat Format output (default 'DD/MM/YYYY').
       * @returns Tanggal hari ini dalam format output.
       */
      today(outputformat?: string): string;
  
      /**
       * Mengambil tanggal awal bulan dalam format yang ditentukan.
       * @param outputformat Format output (default 'DD/MM/YYYY').
       * @returns Tanggal awal bulan dalam format output.
       */
      startMonth(outputformat?: string): string;
  
      /**
       * Mengambil tanggal akhir bulan dalam format yang ditentukan.
       * @param outputformat Format output (default 'DD/MM/YYYY').
       * @returns Tanggal akhir bulan dalam format output.
       */
      lastMonth(outputformat?: string): string;
  
      /**
       * Mengambil daftar akses dari objek yang diberikan.
       * @param access Objek akses (nullable).
       * @param codeOnly Jika true, hanya mengembalikan kode akses (default false).
       * @returns Array akses atau kode akses.
       */
      getAccessList(
        access: Record<string, any> | null, 
        codeOnly?: boolean
      ): any[];
  
      /**
       * Mengonversi file menjadi string Base64.
       * @param file Objek file.
       * @returns Promise yang mengembalikan string Base64 dari file.
       */
      convertBase64(file: File): Promise<string>;
  
      /**
       * Fungsi upload file dan mengonversinya ke Base64.
       * @param event Event input file.
       * @returns Promise dengan objek yang memiliki properti name (nama file) dan file (Base64).
       */
      uploaded(event: Event): Promise<{ name: string; file: string }>;
  
      /**
       * Memeriksa apakah item ditutup berdasarkan tanggal closing dan tanggal item.
       * @param closingInv Tanggal closing.
       * @param itemDate Tanggal item.
       * @returns Boolean apakah item ditutup atau tidak.
       */
      closed(closingInv: string, itemDate: string): boolean;
    };
  
    export default Helper;
  }
  