<?php

namespace App\Http\MieClassi;

class DatiRitorno
{
    protected $datiRitornoArray = [
        'success' => true
    ];

    protected $oggettiReplace = [];
    protected $oggettiReload = [];

    protected $rimuoviOggetto = [];

    protected $mostraOggetto = [];

    protected $nascondiOggetto = [];


    /**
     * @param bool $bool
     * @return DatiRitorno
     */
    public function success($bool)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['success' => $bool]);
        return $this;
    }

    /**
     * @param bool|string $idModal
     * @return DatiRitorno
     */
    public function chiudiModal($idModalConCancelletto = '#kt_modal')
    {
        if ($idModalConCancelletto === true) {
            $idModalConCancelletto = '#kt_modal';
        }
        if (!\Str::of($idModalConCancelletto)->startsWith('#')) {
            $idModalConCancelletto = '#' . $idModalConCancelletto;
        }
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['chiudiModal' => $idModalConCancelletto]);
        return $this;
    }

    /**
     * @param string $funzione
     * @return DatiRitorno
     */
    public function eseguiFunzione($funzione)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['eseguiFunzione' => $funzione]);
        return $this;

    }

    /**
     * @param bool $bool
     * @return DatiRitorno
     */
    public function redirect($url)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['redirect' => $url]);
        return $this;
    }

    /**
     * @param int $id
     * @return DatiRitorno
     */
    public function id($id)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['id' => $id]);
        return $this;

    }

    /** Ricarica contenuto oggetto
     * @param bool $bool
     * @return DatiRitorno
     */
    public function oggettoReload($idOggettoSenzaCancelletto, $html)
    {
        $this->oggettiReload[$idOggettoSenzaCancelletto] = base64_encode($html);
        return $this;

    }

    /** Sostituisce contenuto oggetto
     * @param bool $bool
     * @return DatiRitorno
     */
    public function oggettoReplace($idOggettoSenzaCancelletto, $html)
    {
        $this->oggettiReplace[$idOggettoSenzaCancelletto] = base64_encode($html);
        return $this;

    }

    /**
     * @param bool $bool
     * @return DatiRitorno
     */
    public function rimuoviOggetto($selettoreOggetto)
    {
        $this->rimuoviOggetto[] = $selettoreOggetto;
        return $this;

    }

    /**
     * @return DatiRitorno
     */
    public function mostraOggetto($selettoreOggetto)
    {
        $this->mostraOggetto[] = $selettoreOggetto;
        return $this;

    }

    public function nascondiOggetto($selettoreOggetto)
    {
        $this->nascondiOggetto[] = $selettoreOggetto;
        return $this;

    }

    public function keyValue($key, $value)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, [$key => $value]);
        return $this;
    }

    public function message($messaggio)
    {
        $this->datiRitornoArray = array_merge($this->datiRitornoArray, ['message' => $messaggio]);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (count($this->oggettiReload)) {
            $this->datiRitornoArray['oggettiReload'] = $this->oggettiReload;
        }
        if (count($this->oggettiReplace)) {
            $this->datiRitornoArray['oggettiReplace'] = $this->oggettiReplace;
        }
        if (count($this->rimuoviOggetto)) {
            $this->datiRitornoArray['rimuoviOggetti'] = $this->rimuoviOggetto;
        }
        if (count($this->mostraOggetto)) {
            $this->datiRitornoArray['mostraOggetto'] = $this->mostraOggetto;
        }
        if (count($this->nascondiOggetto)) {
            $this->datiRitornoArray['nascondiOggetto'] = $this->nascondiOggetto;
        }

        return $this->datiRitornoArray;
    }


}
