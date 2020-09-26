<?php

namespace ModernGame\Controller\Front;

use ModernGame\Exception\ContentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class FileListController extends AbstractController
{
    const LAUNCHER_REQUIRE_MOD_PATH = '/launcher/servers/moderngame-1.12.2/forgemods/required/';

    /**
     * @Route(name="modList", path="/modlist")
     */
    public function modList(KernelInterface $appKernel)
    {
        $finder = new Finder();
        try {
            $finder->files()->in($appKernel->getProjectDir() . '/public' . self::LAUNCHER_REQUIRE_MOD_PATH);

            if (!$finder->hasResults()) {
                throw new ContentException([
                    'error' => 'Mod list not found.'
                ]);
            }
        } catch (DirectoryNotFoundException $e) {
            throw new ContentException([
                'error' => 'Mod list not found.'
            ]);
        }

        foreach ($finder as $fileInfo) {
            $files[] = $fileInfo->getRelativePathname();
        }

        return $this->render('front/page/modList.html.twig', [
            'list' => $files ?? [],
            'uri' => self::LAUNCHER_REQUIRE_MOD_PATH
        ]);
    }
}
